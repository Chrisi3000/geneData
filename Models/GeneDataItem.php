<?php

class Models_GeneDataItem extends Models_Base {
    public function findAll(): array {
        $statement = "SELECT 
                        g.id,
                        g.genename,
                        g.genesymbol,
                        g.aliases,
                        g.position,
                        g.function,
                        g.organism_id,
                        o.latin_name AS organism,
                        g.reviewed,
                        g.created_by,
                        u.username as creator
                    FROM genedataitem g
                    JOIN organism o ON o.id = g.organism_id
                    JOIN user u ON u.id = g.created_by";


        $statement = $this->connection->query($statement);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($item) {
            return new Domains_GeneDataItem($item);
        }, $res);
    }

    public function findById($id): Domains_GeneDataItem {
        $statement = "SELECT 
                        g.id,
                        g.genename,
                        g.genesymbol,
                        g.aliases,
                        g.position,
                        g.function,
                        g.organism_id,
                        o.latin_name AS organism,
                        g.reviewed,
                        g.created_by,
                        u.username as creator
                    FROM genedataitem g
                    JOIN organism o ON o.id = g.organism_id
                    JOIN user u ON u.id = g.created_by
                    WHERE g.id = :id";

        $statement = $this->connection->prepare($statement);
        $statement->execute([":id" => $id]);
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($data){
            return new Domains_GeneDataItem($data);
        } else{
            throw new Exceptions_NotFound();
        }
    }

    public function delete($id): void {
        $query = "DELETE FROM genedataitem WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute([":id" => $id]);

        if ($statement->rowCount() === 0) {
            throw new Exceptions_NotFound();
        }
    }

}
