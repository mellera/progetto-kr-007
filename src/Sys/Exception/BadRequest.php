<?php

namespace Sys\Exception;

class BadRequest extends Exception
{

    public function __construct(string $message = "", \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST, $previous, $details);
    }

}
