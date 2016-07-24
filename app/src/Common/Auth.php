<?php
namespace App\Common;

final class Auth {
    public static function checkUser(){
        if (isset($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function getUser(){
        $user = $_SESSION['user'];

        if (isset($user)) {
            return $user;
        } else {
            return null;
        }
    }

    public static function getUserId(){
        $user = self::getUser();

        if(isset($user) && $user != null){
            return $user->id;
        } else {
            return false;
        }
    }
}