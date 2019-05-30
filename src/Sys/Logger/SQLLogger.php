<?php

namespace Sys\Logger;

class SQLLogger implements \Doctrine\DBAL\Logging\SQLLogger
{

    public function startQuery($sql, array $params = null, array $types = null)
    {
        \Log::debug($sql . ' ' . json_encode($params));
    }

    private static function interpolate($message, array $params = null)
    {
        if (is_array($params) && count($params) > 0) {
            while (strstr($message, '?') !== false) {
                $from = '/' . preg_quote('?', '/') . '/';

                $message = preg_replace($from, "'" . array_shift($params) . "'", $message, 1);
            }
        }

        if (is_array($params)) {
            foreach ($params as $name => $value) {
                if (is_numeric($value)) {
                    $message = str_replace(':' . $name, $value, $message);
                } else {
                    $message = str_replace(':' . $name, "'" . $value . "'", $message);
                }
            }
        }

        return $message;
    }

    public function stopQuery()
    {
        
    }

}
