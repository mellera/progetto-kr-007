<?php

namespace Sys\Exception;

class UsernameAlreadyTaken extends Exception
{

    public function __construct(string $message = "", int $code = \Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE, \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, $code, $previous, $details);
    }

}
