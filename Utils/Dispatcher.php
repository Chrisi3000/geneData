<?php

require_once __DIR__ . "/../Controllers/GeneDataItem.php";
require_once __DIR__ . "/../Views/Html.php";

class Utils_Dispatcher
{
    public function dispatch() {
        $url_elements = explode("/", $_SERVER['PATH_INFO']);
        $resource_type = $url_elements[1];
        $path_params = array_filter(array_slice($url_elements, 2));

        $view = new Views_Html($resource_type, $path_params);

        try {
            $controller_name = "Controllers_" . $resource_type;
            $controller = new $controller_name($view, $path_params);

            $verb = strtolower($_SERVER['REQUEST_METHOD']);
            $controller->$verb();

            if (!isset($_SESSION["user"])) {
                Utils_Login::register_guest();
            }

        } catch (Throwable $e){
            echo "error occured:" ;
            echo $e->getMessage();
        }

    }
}