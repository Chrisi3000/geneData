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
        $this->view->render(null);
    }

    public function post()
    {
        $obj = new Domains_GeneDataItem($_POST);
        $data = $this->model->insert($obj);

        http_response_code(302);
        header('Location: /geneData/GeneDataItem/' . $data->id);
        die();
    }

    public function put()
    {
        if (isset($this->params[0]) && !isset($GLOBALS["_PUT"]["id"])) {
            $GLOBALS["_PUT"]["id"] = $this->params[0];
        }

        if (!isset($GLOBALS["_PUT"]["id"])) {
            throw new Exception("Id not found");
        }

        $obj = new Domains_GeneDataItem($GLOBALS["_PUT"]);
        $data = $this->model->update($obj);

        http_response_code(201);
        $this->view->render($data);
    }

    public function delete()
    {
        if (!isset($this->params[0])) {
            throw new Exception("Id not found");
        }

        $this->model->delete($this->params[0]);

        http_response_code(204);
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
