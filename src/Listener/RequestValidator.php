<?php

namespace Sys\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use League\JsonGuard\Validator;

class RequestValidator
{

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $content = $request->getContent();

        // Se c'è contenuto verifichiamo che sia un json
        if ($content) {
            $data = json_decode($content, true);

            if (json_last_error() != JSON_ERROR_NONE) {
                throw new \Sys\Exception\InvalidJsonFormat(json_last_error_msg());
            }
        } else { // Altrimenti inizializziamo le variabili
            $content = array();
            $data = array();
        }

        // Cerco lo schema per la determinata richiesta
        $schema = $this->loadSchema($request->attributes->get('__controller'), $request->attributes->get('__function'));

        // Se esiste uno schema lo confrontiamo con quello che ci passano
        if ($schema) {
            $validator = new Validator(json_decode($content), json_decode($schema));

            // Se non passa i controlli
            if (!$validator->passes()) {
                throw (new \Sys\Exception\InvalidJsonData())->SetDetails($validator->errors());
            }
        }

        $request->attributes->set('content', $data);
    }

    private function loadSchema(string $controller, string $function)
    {
        $filename = ETC_PATH . '/schemas/' . strtolower(str_replace('\\Controller\\', '', $controller)) . '/' . strtolower($function) . '.json';

        \Sys\Context::logger()->debug($filename);

        if (file_exists($filename)) {
            return file_get_contents($filename);
        }

        return false;
    }

}
