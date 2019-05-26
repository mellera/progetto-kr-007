<?php

namespace Sys\Exception;

class LoginFailed extends Exception
{

    public function __construct(string $message = "", int $code = \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED, \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, $code, $previous, $details);
    }

}
