<?php

namespace Sys\Config;

abstract class Web implements \Sys\Config
{

    public function getLoggerBasePath(): string
    {
        return 'var/logs/http/web';
    }

    public function getLogLevel(): string
    {
        return \Psr\Log\LogLevel::INFO;
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
        return '[ {{H}}:{{i}}:{{s}} ][ {{request:id}} ][ {{log:level}} ][ {{caller:file}} ][ {{caller:info}} ] ';
    }

    public function handleException(\Throwable $ex)
    {
        header('HTTP/1.1 500 Internal Server Error', true, 500);
        header('Content-type: application/json');

        $response = array(
            'status' => 500,
            'details' => array('exception' => get_class($ex)),
            'description' => $ex->getMessage()
        );

        echo json_encode($response);
    }

}
