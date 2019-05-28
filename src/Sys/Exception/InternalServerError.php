<?php

namespace Sys\Exception;

class InternalServerError extends Exception
{

    public function __construct(string $message = "", \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR, $previous, $details);
    }

}
