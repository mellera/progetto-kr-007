<?php

namespace Sys\Listener\Api;

class RequestLogger
{

    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        \Log::info($event->getRequest()->getMethod() . ' ' . $event->getRequest()->getRequestUri());
    }

}
