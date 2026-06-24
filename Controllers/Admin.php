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
}