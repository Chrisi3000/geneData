<?php

class Controllers_Admin extends Controllers_Base {
    private $model;

    // instantiates user management model onto the base admin routing layer
    public function __construct(Views_Base $view, array $params) {
        parent::__construct($view, $params);
        $this->model = new Models_User();
    }

    // renders administrative control screens containing all registered profile instances
    public function get(){
        // blocks regular non-elevated profile accounts at access gate checkpoints
        if (!Utils_Login::is_admin()) {
            http_response_code(403);
            $this->view->render(new Exception("Unauthorized"));
            return;
        }
        $data = $this->model->findAll();
        $this->view->render($data);
    }

    // handles partial resource modifications to toggle administrative authority settings
    public function patch(){
        if (!Utils_Login::is_admin()) {
            http_response_code(403);
            $this->view->render(new Exception("Unauthorized"));
            return;
        }

        $id = isset($this->params[0]) ? (int)$this->params[0] : null;
        $data = $GLOBALS["_PATCH"];

        // validates parameter requirements to avoid malformed record alterations
        if ($id === null || $id === 0) {
            http_response_code(400);
            return;
        }
        if (!isset($data["is_admin"])) {
            http_response_code(400);
            return;
        }

        $current_logged_in_id = Utils_Login::get_user_id();

        // prevents self-demotion anomalies by locking alterations to the active user's own token
        if ($current_logged_in_id !== null && $id === $current_logged_in_id) {
            http_response_code(400);
            echo json_encode(["error" => "Cannot change yourself"]);
            return;
        }

        // executes structural state update logic via persistence layers
        $this->model->setToAdmin($id, $data["is_admin"]);

        http_response_code(200);
        echo json_encode(["success" => true]);
        return;
    }

    // permanently destroys a targeted user record if security parameters clear safely
    public function delete() {
        if(!Utils_Login::is_admin()){
            throw new Exceptions_Unauthorized("Unauthorized");
        }

        $id = $this->params[0] ?? null;

        // normalizes endpoint nesting routing structures to fetch target key identifiers
        if ($id === "users") {
            $id = $this->params[1] ?? null;
        }

        if($id === null){
            throw new Exception("Id not found");
        }

        // guard clause preventing self-deletion lockouts inside the persistence grid
        if ((int)$id === (int)Utils_Login::get_user_id()) {
            throw new Exception("You cannot delete your own user.");
        }

        $this->model->delete($id);
        http_response_code(204);
    }
}
