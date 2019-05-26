<?php

namespace Sys\Response;

class ErrorJson extends \Symfony\Component\HttpFoundation\JsonResponse
{

    /**
     * 
     * @param int $status
     * @param string $message
     * @param array $details
     */
    public function __construct(int $status, string $message = null, array $details = array())
    {
        parent::__construct(null, $status, array('Access-Control-Allow-Origin' => '*'), false);

        $data = array(
            'status' => $status,
            'description' => !empty($message) ? $message : self::$statusTexts[$status]
        );

        if (count($details)) {
            $data['details'] = $details;
        }

        $this->setData($data);
    }

}
