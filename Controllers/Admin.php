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
