<?php

class Models_User extends Models_Base{
    public function login($user, $password) : ?Domains_User {
        $query = "SELECT id, username, password, is_admin 
                FROM user WHERE username = :username";
        $statement = $this->connection->prepare($query);
        $statement->execute([":username" => $user]);
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if(!$data) {
            return null;
        }

        $user = new Domains_User($data);
        if(password_verify($password, $user->password)){
            return $user;
        }
        return null;
    }

    public function createUser($user, $password) {
        $query = "INSERT INTO user (username, password) VALUES (:username, :password)";
        $statement = $this->connection->prepare($query);
        $statement->execute([":username" => $user, ":password" => $password]);
    }

    public function findByUsername($user) {
        $query = "SELECT * FROM user WHERE username = :username";
        $statement = $this->connection->prepare($query);
        $statement->execute([":username" => $user]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll(): array {
        $statement = "SELECT id, username, is_admin FROM user";

        $statement = $this->connection->query($statement);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($item) {
            return new Domains_User($item);
        }, $res);
    }
}