<?php

namespace Sys\Exception;

class MethodNotAllowed extends Exception
{

    public function __construct(string $message = "", \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, \Symfony\Component\HttpFoundation\Response::HTTP_METHOD_NOT_ALLOWED, $previous, $details);
    }

}
