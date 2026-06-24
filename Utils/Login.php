<?php

class Utils_Login {
    public static function register_session($user): void {
        $_SESSION["user"] = $user;
        $_SESSION["logged_in"] = $user->id !== null;
        $_SESSION["is_admin"] = (int)$user->is_admin;
    }

    static function register_guest(): void{
        $user = new Domains_User([
            "id" => null,
            "username" => "guest",
            "password" => null,
            "is_admin" => 0
        ]);
        Utils_Login::register_session($user);
    }

    static function delete_session(){
        unset($_SESSION['user']);
        unset($_SESSION['is_admin']);
        unset($_SESSION['logged_in']);
    }

    static function check_session_or_error(): void{
        if(!isset($_SESSION['user'])){
            throw new Exceptions_Unauthorized("Unauthorized");
        }
    }

    public static function is_admin():bool{
        return !empty($_SESSION['is_admin']);
    }

    public static function is_logged_in():bool{
        return !empty($_SESSION['logged_in']);
    }
}