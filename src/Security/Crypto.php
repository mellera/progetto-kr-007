<?php

namespace Security;

use Defuse\Crypto\Key;

class Crypto
{

    /**
     * 
     * @param string $plainText
     * @return string
     */
    public static function encrypt(string $plainText): string
    {
        return \Defuse\Crypto\Crypto::encrypt($plainText, self::key());
    }

    /**
     * 
     * @param string $chiperText
     * @return string
     */
    public static function decrypt(string $chiperText): string
    {
        return \Defuse\Crypto\Crypto::decrypt($chiperText, self::key());
    }

    /**
     * 
     * @return string
     */
    public static function createKey(): string
    {
        return Key::createNewRandomKey()->saveToAsciiSafeString();
    }

    /**
     * 
     * @return object
     */
    private static function key()
    {
        return Key::loadFromAsciiSafeString(\Sys\Context::getEncryptionKey());
    }

}
