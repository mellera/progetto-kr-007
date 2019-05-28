<?php

namespace Sys\Listener\Web;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;

class View
{

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        if ($controllerResult instanceof \Sys\View\Block) {
            $event->setResponse(new Response($controllerResult->render(), Response::HTTP_OK));
        }
    }

}
