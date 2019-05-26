<?php

namespace Sys\Exception;

class Exception extends \Exception
{

    private $details;

    public function __construct(string $message = "", int $code = \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR, \Throwable $previous = null, array $details = array())
    {
        parent::__construct($message, $code, $previous);

        $this->SetDetails($details);
    }

    public function SetDetails(array $details)
    {
        $this->details = $details;

        return $this;
    }

    public function GetDetails()
    {
        return $this->details;
    }

}
