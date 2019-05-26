<?php

namespace Sys\Listener\Web;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;

class View
{

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $controlleResult = $event->getControllerResult();

        if ($controlleResult instanceof \Sys\View\Block) {
            $event->setResponse(new Response($controlleResult->render(), Response::HTTP_OK));
        }
    }

}
