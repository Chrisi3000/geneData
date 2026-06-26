<?php

require_once "Base.php";

class Views_Html extends Views_Base
{
    public function render($data)
    {
        if (is_array($data)) {
            $template = "table.phtml";
        }
        else if($data === "login"){
            $template = "login.phtml";
        }
        else if($data === "register"){
            $template = "register.phtml";
        }
        else{
            $template = "object.phtml";
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $trimmedPath = rtrim($path, '/');

        if (str_ends_with($trimmedPath, '/create')) {
            $template = "create.phtml";
        }

        else if (str_contains($trimmedPath, '/edit/') || str_ends_with($trimmedPath, '/edit')) {
            $template = "edit.phtml";
        }

        if (is_readable(dirname(__FILE__) . "/templates/" . $this->resource_name . "/" . $template)) {
            $template = $this->resource_name . "/" . $template;
        }

        if($data instanceof Exception){
            $template = "error.phtml";
        }

        include "templates/" . $template;
        exit;
    }
}