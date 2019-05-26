<?php

namespace Sys\Exception;

class UnprocessableEntity extends Exception
{

    public function __construct(string $message = "", int $code = \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY, \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, $code, $previous, $details);
    }

}
