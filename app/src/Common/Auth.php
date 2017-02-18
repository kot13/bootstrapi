<?php
namespace App\Common;

use App\Model\User;

final class Auth
{
    /**
     * Forbidden to create new instances
     */
    private function __construct()
    {

    }

    /**
     * Forbidden to cloned instances
     */
    private function __clone()
    {

    }

    /**
     * @var User
     */
    private static $user = null;

    /**
     * @param User $user
     */
    public static function setUser(User $user)
    {
        self::$user = $user;
    }

    /**
     * @return bool
     */
    public static function checkUser()
    {
        return !is_null(self::$user);
    }

    /**
     * @return User
     */
    public static function getUser()
    {
        return self::$user;
    }

    /**
     * @return int|null
     */
    public static function getUserId()
    {
        if (self::checkUser()) {
            return self::$user->id;
        } else {
            return null;
        }
    }
}
