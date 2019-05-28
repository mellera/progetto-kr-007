<?php

namespace Sys\Config;

abstract class Phinx extends \Sys\Config
{

    public function getLoggerBasePath(): string
    {
        return 'var/logs/phinx';
    }

    public function getLogLevel(): string
    {
        return \Psr\Log\LogLevel::DEBUG;
    }

    public function handleException(\Throwable $ex)
    {
        echo 'Exception ' . $ex->getMessage() . PHP_EOL;
    }

}
