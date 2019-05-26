<?php

namespace Sys\Listener;

use Symfony\Component\HttpFoundation\Response;

class Exception
{

    public function onKernelException(\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();

        \Sys\Context::logger()->debug(get_class($e) . ' ' . $e->getFile() . '::' . $e->getLine());
        \Sys\Context::logger()->error($e->getMessage() . PHP_EOL . $e->getTraceAsString());

        $details = array();

        $details['exception'] = get_class($e);

        if ($e instanceof \Sys\Exception\Exception) {
            $status = $e->getCode();
            $details = array_merge($details, $e->GetDetails());
        } else if ($e instanceof \Symfony\Component\Routing\Exception\NoConfigurationException) {
            $status = Response::HTTP_NOT_IMPLEMENTED;
        } else if ($e instanceof \Symfony\Component\Routing\Exception\MethodNotAllowedException) {
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
        } else if ($e instanceof \Symfony\Component\Routing\Exception\ResourceNotFoundException) {
            $status = Response::HTTP_NOT_FOUND;
        } else {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response = new \Sys\Response\ErrorJson($status, $e->getMessage(), $details);

        $event->setResponse($response);
    }

}
