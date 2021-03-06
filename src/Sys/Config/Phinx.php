<?php

namespace Sys\Config;

abstract class Phinx implements \Sys\Config
{

    public function getLoggerBasePath(): string
    {
        return 'var/logs/phinx';
    }

    public function getLogLevel(): string
    {
        return \Psr\Log\LogLevel::DEBUG;
    }
    
    public function getLoggerPath(): string
    {
        return '{{Y}}/{{m}}/{{d}}';
    }

    public function getLoggerFilename(): string
    {
        return '{{user:username}}.log';
    }

    public function getLoggerPrefix(): string
    {
        return '[ {{H}}:{{i}}:{{s}} ][ {{log:level}} ][ {{caller:file}} ][ {{caller:info}} ] ';
    }

    public function handleException(\Throwable $ex)
    {
        echo 'Exception ' . $ex->getMessage() . PHP_EOL;
    }

}
