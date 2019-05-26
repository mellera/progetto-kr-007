<?php

namespace Sys\Exception;

class InvalidUser extends Exception
{

    public function __construct(string $message = "", int $code = \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, $code, $previous, $details);
    }

}
