<?php

namespace Sys\Security;

class JWT
{

    /**
     * 
     * @param array|object $payload
     * @return string
     */
    public static function encode($payload): string
    {
        return \Firebase\JWT\JWT::encode($payload, \Sys\Context::getAppKey());
    }

    /**
     * 
     * @param string $jwt
     * @return array|object
     */
    public static function decode(string $jwt)
    {
        return \Firebase\JWT\JWT::decode($jwt, \Sys\Context::getAppKey(), array('HS256'));
    }

}
