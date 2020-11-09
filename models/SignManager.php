<?php

namespace models;

use exceptions\SignException;

/**
 * Manager SignManager
 *
 * @package models
 */
class SignManager
{
    /**
     * Logs an user in
     *
     * @param $login
     * @param $password
     *
     * @return bool
     * @throws SignException
     */
    static function SignIn($login, $password)
    {
        (session_status() === 1 ? session_start() : null);
        if (self::checkUsername($login)) {
            $DBPass = DbManager::requestUnit("SELECT password FROM user WHERE username = ?", [$login]);
            if (password_verify($password, $DBPass)) {
                return true;
            } else {
                throw new SignException("Wrong password");
            }
        } else {
            throw new SignException("Wrong login");
        }
    }

    /**
     * Check if users username is used
     *
     * @param $username
     *
     * @return bool
     */
    static function checkUsername($username)
    {
        return (DbManager::requestAffect("SELECT username FROM user WHERE username = ?", [$username]) === 1);
    }

}
