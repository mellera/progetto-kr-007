<?php

namespace Sys\Response;

class CorrectJson extends \Symfony\Component\HttpFoundation\JsonResponse
{

    public function __construct($details = null, $responseStatus = \Symfony\Component\HttpFoundation\Response::HTTP_OK)
    {
        parent::__construct(null, $responseStatus, array('Access-Control-Allow-Origin' => '*'), false);

        $data = array(
            'status' => $responseStatus,
            'details' => $details
        );

        $this->setData($data);
    }

}
