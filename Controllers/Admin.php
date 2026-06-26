<?php

class Controllers_Admin extends Controllers_Base {
    private $model;

    public function __construct(Views_Base $view, array $params) {
        parent::__construct($view, $params);
        $this->model = new Models_User();
    }

    public function get(){
        //check if user is admin
        if (!Utils_Login::is_admin()) {
            http_response_code(403);
            $this->view->render(new Exception("Unauthorized"));
            return;
        }
        $data = $this->model->findAll();
        $this->view->render($data);
    }

    public function patch(){
        if (!Utils_Login::is_admin()) {
            http_response_code(403);
            $this->view->render(new Exception("Unauthorized"));
            return;
        }

        $id = isset($this->params[0]) ? (int)$this->params[0] : null;
        $data = $GLOBALS["_PATCH"];

        if ($id === null || $id === 0) {
            http_response_code(400);
            return;
        }
        if (!isset($data["is_admin"])) {
            http_response_code(400);
            return;
        }

        $current_logged_in_id = isset($_SESSION["id"]) ? (int)$_SESSION["id"] : null;

        if ($current_logged_in_id !== null && $id === $current_logged_in_id) {
            http_response_code(400);
            echo json_encode(["error" => "Cannot change yourself"]);
            return;
        }

        //save admin state
        $this->model->setToAdmin($id, $data["is_admin"]);

        http_response_code(200);
        echo json_encode(["success" => true]);
        return;
    }

    public function delete() {
        if(!Utils_Login::is_admin()){
            throw new Exceptions_Unauthorized("Unauthorized");
        }

        $id = $this->params[0] ?? null;

        if ($id === "users") {
            $id = $this->params[1] ?? null;
        }

        if($id === null){
            throw new Exception("Id not found");
        }

        if ((int)$id === (int)Utils_Login::get_user_id()) {
            throw new Exception("You cannot delete your own user.");
        }

        $this->model->delete($id);
        http_response_code(204);
    }
}