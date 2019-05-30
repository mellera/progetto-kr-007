<?php

namespace Sys\Security;

class Password
{

    /**
     * 
     * @param string $password
     * @return string
     */
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

}
