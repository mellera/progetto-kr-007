<?php

namespace Sys\Config;

class Api extends \Sys\Config
{

    public function getLoggerBasePath(): string
    {
        return 'var/logs/http/api';
    }

    public function getLogLevel(): string
    {
        return \Psr\Log\LogLevel::INFO;
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
