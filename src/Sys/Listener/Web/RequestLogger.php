<?php

namespace Sys\Listener\Web;

use Sys\Context as Context;

class RequestLogger
{

    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {        
        $logger = new \Sys\Logger\Logger(Context::config()->getRootPath(), 'var/logs/http/webEvents');

        $logger->setPath('{{Y}}/{{m}}');
        $logger->setFilename('{{d}}.event');
        $logger->setPrefix('[ {{H}}:{{i}}:{{s}} ]');
        $logger->setLogLevel(\Psr\Log\LogLevel::INFO);

        $logger->info('[ ' . $event->getRequest()->getMethod() . ' ][ ' . $event->getRequest()->getRequestUri() . ' ]');
    }

}
