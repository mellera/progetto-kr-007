<?php

namespace Sys\Listener;

class Router
{

    private $matcher;

    public function __construct(\Symfony\Component\Routing\Matcher\RequestMatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $parameters = $this->matcher->matchRequest($request);

        $request->attributes->add($parameters);
    }

}
