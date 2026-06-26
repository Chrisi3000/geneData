<?php

require_once __DIR__ . "/../Controllers/GeneDataItem.php";
require_once __DIR__ . "/../Views/Html.php";

class Utils_Dispatcher
{
    public function dispatch() {

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url_elements = explode("/", trim($path, "/"));

        $resource_type = $url_elements[1];
        $path_params = array_filter(array_slice($url_elements, 2));

        $verb = strtolower($_SERVER['REQUEST_METHOD']);
            if ($verb === "get" && isset($path_params[0]) && $path_params[0] === "create") {
                $verb = "create";
                array_shift($path_params);
            }

        $action = null;

        if (isset($path_params[0]) && in_array($path_params[0], ['create', 'edit'])) {
            $action = $path_params[0];
            array_shift($path_params);
        }

        $view = new Views_Html($resource_type, $verb, $path_params);
        $controller_name = "Controllers_" . $resource_type;
        $controller = new $controller_name($view, $path_params);

        try {

            if ($verb === "post" && isset($_POST['_method'])) {
                $override = strtolower($_POST['_method']);
                if (in_array($override, ['put', 'delete'])) {
                    $verb = $override;
                }
            }

            if ($verb === "put") {
                parse_str(file_get_contents("php://input"), $GLOBALS["_PUT"]);
            }

            if ($verb === "delete") {
                parse_str(file_get_contents("php://input"), $GLOBALS["_DELETE"]);
            }

            if ($verb === "patch") {
                var_dump($verb);
                $GLOBALS["_PATCH"] = json_decode(file_get_contents("php://input"), true);
            }

            if ($action && method_exists($controller, $action)) {
                $controller->$action();
            } elseif (method_exists($controller, $verb)) {
                $controller->$verb();
            } else {
                throw new Exception("Method not allowed");
            }

            if (!isset($_SESSION["user"])) {
                Utils_Login::register_guest();
            }

        } catch (Throwable $e) {
            echo "error occurred: ";
            echo $e->getMessage();
        }
    }
}