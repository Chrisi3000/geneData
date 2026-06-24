<?php

require_once "Base.php";

class Views_Html extends Views_Base
{

    public function render($data)
    {
        // 1. Standard-Templates definieren
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

        // 2. Dynamisch prüfen, ob wir uns im "create"-Modus befinden
        // Wir holen uns den aktuellen URL-Pfad
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Wenn die URL auf "/create" endet, erzwingen wir das create.phtml Template
        if (str_ends_with(rtrim($path, '/'), '/create')) {
            $template = "create.phtml";
        }

        // 3. Prüfen, ob das spezifische Template im Ordner existiert (z.B. templates/GeneDataItem/create.phtml)
        if (is_readable(dirname(__FILE__) . "/templates/" . $this->resource_name . "/" . $template)) {
            $template = $this->resource_name . "/" . $template;
        }

        if($data instanceof Exception){
            $template = "error.phtml";
        }

        // 4. Template einbinden
        include "templates/" . $template;
        exit;
    }
}