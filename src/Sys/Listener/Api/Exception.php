<?php

namespace Sys\Listener\Api;

use Symfony\Component\HttpFoundation\Response;

class Exception
{

    public function onKernelException(\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();

        \Log::debug(get_class($e) . ' ' . $e->getFile() . '::' . $e->getLine());
        \Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());

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

        $response = new \Symfony\Component\HttpFoundation\JsonResponse(null, $status, array('Access-Control-Allow-Origin' => '*'), false);

        $data = array(
            'status' => $status,
            'description' => $e->getMessage() ?? Response::$statusTexts[$status]
        );

        if (count($details)) {
            $data['details'] = $details;
        }

        $response->setData($data);

        $event->setResponse($response);
    }

}
