<?php

class Controllers_Login extends Controllers_Base {
   private Models_User $model;

   public function __construct(Views_Base $views, array $params) {
       parent::__construct($views, $params);
       $this->model = new Models_User();
   }

   public function get() {
       $data = "login";
       $this->view->render($data);
   }

   public function post(){
       if(isset($_POST["guest"])){
           $this->guest();
       }

       $username = $_POST["username"];
       $pw = $_POST["password"];

       $user = $this->model->login($username, $pw);
       if($user == null){
           $_SESSION["login_error"] = "Username or password is incorrect.";
           header("Location: /geneData/Login");
           exit();
       }

       Utils_Login::register_session($user);
       header("Location: /geneData/GeneDataItem");
       die();
   }

    public function guest() {
        Utils_Login::register_guest();

       header("Location: /geneData/GeneDataItem");
       exit();
    }

    public static function is_admin(): bool {
       return isset($_SESSION['user']['is_admin'])
           && $_SESSION['user']['is_admin'] == 1;
    }
}