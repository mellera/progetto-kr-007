<?php

use Sys\Context;

class Log
{

    public static function debug($message, array $context = array())
    {
        Context::logger()->debug($message, $context);
    }

    public static function info($message, array $context = array())
    {
        Context::logger()->info($message, $context);
    }

    public static function notice($message, array $context = array())
    {
        Context::logger()->notice($message, $context);
    }

    public static function warning($message, array $context = array())
    {
        Context::logger()->warning($message, $context);
    }

    public static function error($message, array $context = array())
    {
        Context::logger()->error($message, $context);
    }

    public static function critical($message, array $context = array())
    {
        Context::logger()->critical($message, $context);
    }

    public static function alert($message, array $context = array())
    {
        Context::logger()->alert($message, $context);
    }

    public static function emergency($message, array $context = array())
    {
        Context::logger()->emergency($message, $context);
    }

    public static function exception($ex)
    {
        self::error('[ EXCEPTION ][ ' . $ex->getFile() . '::' . $ex->getLine() . ' ] ' . $ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
    }

}
