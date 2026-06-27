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

        // Checks if the first URL parameter defines a specific action like create or edit
        if (isset($path_params[0]) && in_array($path_params[0], ['create', 'edit'])) {
            $action = $path_params[0];
            array_shift($path_params); // Removes create/edit from parameters so only IDs/arguments remain
        }

        $view = new Views_Html($resource_type, $verb, $path_params);
        $controller_name = "Controllers_" . $resource_type;
        $controller = new $controller_name($view, $path_params);

        try {

            // Simulates PUT/DELETE via standard POST requests using a hidden _method field
            if ($verb === "post" && isset($_POST['_method'])) {
                $override = strtolower($_POST['_method']);
                if (in_array($override, ['put', 'delete'])) {
                    $verb = $override;
                }
            }

            // Parses incoming raw data stream into global variables for non-POST data types
            if ($verb === "put") {
                parse_str(file_get_contents("php://input"), $GLOBALS["_PUT"]);
            }

            if ($verb === "delete") {
                parse_str(file_get_contents("php://input"), $GLOBALS["_DELETE"]);
            }

            if ($verb === "patch") {
                $GLOBALS["_PATCH"] = json_decode(file_get_contents("php://input"), true);
            }

            // Prioritizes explicit action methods over HTTP fallback verbs
            if ($action && method_exists($controller, $action)) {
                $controller->$action();
                // Terminates execution instantly for API requests to prevent the accidental guest session overwrite below
                if (in_array($verb, ['put', 'delete', 'patch'])) {
                    exit();
                }
            } elseif (method_exists($controller, $verb)) {
                $controller->$verb();
                if (in_array($verb, ['put', 'delete', 'patch'])) {
                    exit();
                }
            } else {
                throw new Exception("Method not allowed");
            }

            // If the user has no authenticated session state register them automatically as a guest
            if (!Utils_Login::is_logged_in() && !isset($_SESSION["user"])) {
                Utils_Login::register_guest();
            }

        } catch (Throwable $e) {
            echo "error occurred: ";
            echo htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8");
        }
    }
}
