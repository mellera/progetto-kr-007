<?php

namespace Sys\Exception;

class Unauthorized extends Exception
{

    public function __construct(string $message = "", \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED, $previous, $details);
    }

}
