<?php

namespace Sys\Exception;

class UnprocessableEntity extends Exception
{

    public function __construct(string $message = "", \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY, $previous, $details);
    }

}
