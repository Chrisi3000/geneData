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
