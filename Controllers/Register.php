<?php

class Controllers_Register extends Controllers_Base {
   private Models_User $model;

   public function __construct(Views_Base $views, array $params) {
       parent::__construct($views, $params);
       $this->model = new Models_User();
   }

    public function get() {
        $data = "register";
        $this->view->render($data);
    }

    public function post(){
       $username = $_POST["username"];
       $pw = $_POST["password"];
       $hash_password = password_hash($pw, PASSWORD_DEFAULT);

        if ($this->model->findByUsername($username) != null) {
            $_SESSION["register_error"] = "User already exists.";
            header("Location: /geneData/Register");
            exit();
        }

       $this->model->createUser($username, $hash_password);

       $user = $this->model->login($username, $pw);

       Utils_Login::register_session($user);
       header("Location: /geneData/Login");
       die();
   }
}