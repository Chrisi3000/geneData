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
       if($_POST["action"] == "guest") {
           $this->guest();
           exit();
       }

       $input_username = $_POST["username"] ?? null;
       $input_password = $_POST["password"] ?? null;

       if($input_username == null || $input_password == null){
           $_SESSION["login_error"] = "Username or password is not entered.";
           header("Location: /geneData/Login");
           exit();
       }

       $username = $input_username;
       $pw = $input_password;

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