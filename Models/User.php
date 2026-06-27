<?php

class Models_User extends Models_Base{
    private function validateId($id): int {
        if (!filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            throw new Exceptions_NotFound();
        }

        $id = (int)$id;
        $query = "SELECT id FROM user WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([":id" => $id]);

        if (!$statement->fetch(PDO::FETCH_ASSOC)) {
            throw new Exceptions_NotFound();
        }

        return $id;
    }

    // fetches user and validates raw password against the stored secure hash
    public function login($user, $password) : ?Domains_User {
        $query = "SELECT id, firstname, lastname, username, password, is_admin 
                FROM user WHERE username = :username";
        $statement = $this->connection->prepare($query);
        $statement->execute([":username" => $user]);
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if(!$data) {
            return null;
        }

        // instantiates domain object to safely verify the encrypted password string
        $user = new Domains_User($data);
        if(password_verify($password, $user->password)){
            return $user;
        }
        return null;
    }

    // inserts a new user record with standard column credentials
    public function createUser($user, $password, $fn, $ln) {
        $query = "INSERT INTO user (username, password, firstname, lastname) VALUES (:username, :password, :firstname, :lastname)";
        $statement = $this->connection->prepare($query);
        $statement->execute([":username" => $user, ":password" => $password, ":firstname" => $fn, ":lastname" => $ln]);
    }

    // returns raw associative array user dataset matching a specific unique username
    public function findByUsername($user) {
        $query = "SELECT * FROM user WHERE username = :username";
        $statement = $this->connection->prepare($query);
        $statement->execute([":username" => $user]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // maps all database user rows into collections of typed domain entities
    public function findAll(): array {
        $statement = "SELECT id, firstname, lastname, username, is_admin FROM user";

        $statement = $this->connection->query($statement);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($item) {
            return new Domains_User($item);
        }, $res);
    }

    // updates administrative privilege flag settings for a specific user id row
    public function setToAdmin($id, $is_admin) {
        $id = $this->validateId($id);

        $query = "UPDATE user SET is_admin = :is_admin WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            ":is_admin" => $is_admin ? 1 : 0,
            ":id" => $id
        ]);
    }

    // removes a user within an isolated database transaction block to safely clear foreign keys
    public function delete($id): void {
        $id = $this->validateId($id);

        $this->connection->beginTransaction();

        try {
            // updates dependent records to avoid strict foreign key constraint blockages
            $query = "UPDATE genedataitem SET created_by = NULL WHERE created_by = :id";
            $statement = $this->connection->prepare($query);
            $statement->execute([":id" => $id]);

            // removes the targeted user data row permanently from persistence layer
            $query = "DELETE FROM user WHERE id = :id";
            $statement = $this->connection->prepare($query);
            $statement->execute([":id" => $id]);

            if ($statement->rowCount() === 0) {
                throw new Exceptions_NotFound();
            }

            $this->connection->commit();
        } catch (Throwable $e) {
            // rolls back database alterations instantly to keep information safe on errors
            $this->connection->rollBack();
            throw $e;
        }
    }
}
