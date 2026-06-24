<?php

class Domains_User extends Domains_Base{

    public function __construct($data){
        $this->data = [
            "id"=>null,
            "username"=>null,
            "password"=>null,
            "is_admin"=>null
        ];

        parent::__construct($data);
    }
}