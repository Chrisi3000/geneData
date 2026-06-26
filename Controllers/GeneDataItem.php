<?php

require_once "Base.php";

class Controllers_GeneDataItem extends Controllers_Base {
    private $model;

    public function __construct(Views_Base $view, array $params) {
        parent::__construct($view, $params);
        $this->model = new Models_GeneDataItem();
    }

    public function get(){
        if($this->params){
            $data = $this->model->findById($this->params[0]);
        } else{
            $data = $this->model->findAll();
        }

        $this->view->render($data);
    }

    public function create()
    {
        $organismModel = new Models_Organism();
        $organisms = $organismModel->findAll();

        $this->view->render($organisms);
    }

    public function post()
    {
        if (!Utils_Login::is_logged_in()) {
            throw new Exceptions_Unauthorized("Unauthorized");
        }

        $data = $_POST;

        $data["reviewed"] = isset($data["reviewed"]) ? 1 : 0;
        $data["created_by"] = $_SESSION["user_id"];

        $obj = new Domains_GeneDataItem($data);
        $result = $this->model->insert($obj);

        header("Location: /geneData/GeneDataItem/" . $result->id);
        exit();
    }

    public function put()
    {
        if (!Utils_Login::is_logged_in()) {
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized"]);
            exit();
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($this->params[0]) && !isset($input["id"])) {
            $input["id"] = $this->params[0];
        }

        if (!isset($input["id"])) {
            http_response_code(400);
            echo json_encode(["error" => "Id not found"]);
            exit();
        }

        $input["reviewed"] = (isset($input["reviewed"]) && $input["reviewed"] == "1") ? 1 : 0;

        $_POST = $input;
        $_REQUEST = array_merge($_REQUEST, $input);

        try {
            $obj = new Domains_GeneDataItem($input);

            $this->model->update($obj);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(["success" => true]);
            exit();
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(["error" => $e->getMessage()]);
            exit();
        }
    }

    public function edit()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if (!Utils_Login::is_logged_in()) {
            throw new Exceptions_Unauthorized("Unauthorized");
        }

        if (!isset($this->params[0])) {
            throw new Exception("Id not found");
        }


        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $input = json_decode(file_get_contents("php://input"), true);

            if (!isset($input["id"])) {
                $input["id"] = $this->params[0];
            }

            $input["reviewed"] = (!empty($input["reviewed"]) && $input["reviewed"] === true) ? 1 : 0;

            $_POST = $input;

            try {
                $obj = new Domains_GeneDataItem($input);
                echo "<h4>Inhalt von \$input:</h4><pre>" . print_r($input, true) . "</pre>";
                echo "<h4>Inhalt vom Objekt:</h4><pre>" . print_r($obj, true) . "</pre>";
                exit;
                $this->model->update($obj);

                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode(["success" => true]);
                exit();
            } catch (Exception $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(["error" => $e->getMessage()]);
                exit();
            }
        }

        $geneItem = $this->model->findById($this->params[0]);
        if (!$geneItem) {
            throw new Exception("Gene not found");
        }

        $organismModel = new Models_Organism();
        $organisms = $organismModel->findAll();

        $this->view->render([
            'gene' => $geneItem,
            'organisms' => $organisms
        ]);
    }

    public function delete() {
        if(!Utils_Login::is_logged_in()){
            throw new Exceptions_Unauthorized("Unauthorized");
        }

        if(!isset($this->params[0])){
            throw new Exception("Id not found");
        }

        $this->model->delete($this->params[0]);
        http_response_code(204);
    }
}
