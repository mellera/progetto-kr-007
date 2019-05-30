<?php

namespace Sys\Exception;

class NotAcceptable extends Exception
{

    public function __construct(string $message = "", \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, \Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE, $previous, $details);
    }

}
