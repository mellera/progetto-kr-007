<?php

namespace Sys\Listener;

use Sys\Context as Context;

class RequestLogger
{

    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        Context::logger()->info($event->getRequest()->getMethod() . ' ' . $event->getRequest()->getRequestUri());
    }

}
