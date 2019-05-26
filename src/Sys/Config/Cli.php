<?php

namespace Sys\Config;

class Cli extends \Sys\Config
{

    public function getLoggerBasePath(): string
    {
        return 'var/logs/cli';
    }

    public function getLogLevel(): string
    {
        return \Psr\Log\LogLevel::INFO;
    }

    public function handleException(\Throwable $ex)
    {
        echo 'Exception ' . $ex->getMessage() . PHP_EOL;
    }

}
