<?php

namespace Sys\Logger;

class Logger implements \Psr\Log\LoggerInterface
{

    /**
     * Array of log levels
     */
    const LOG_LEVELS = array(
        \Psr\Log\LogLevel::EMERGENCY => LOG_EMERG,
        \Psr\Log\LogLevel::ALERT => LOG_ALERT,
        \Psr\Log\LogLevel::CRITICAL => LOG_CRIT,
        \Psr\Log\LogLevel::ERROR => LOG_ERR,
        \Psr\Log\LogLevel::WARNING => LOG_WARNING,
        \Psr\Log\LogLevel::NOTICE => LOG_NOTICE,
        \Psr\Log\LogLevel::INFO => LOG_INFO,
        \Psr\Log\LogLevel::DEBUG => LOG_DEBUG
    );

    /**
     *
     * @var string
     */
    private $logLevel = \Psr\Log\LogLevel::DEBUG;

    /**
     *
     * @var string
     */
    private $rootPath = '/';

    /**
     *
     * @var string
     */
    private $baseLogPath = 'logs';

    /**
     *
     * @var string
     */
    private $variablePath = '{{Y}}/{{m}}/{{d}}';

    /**
     *
     * @var string
     */
    private $filename = '{{Y}}-{{m}}-{{d}}.log';

    /**
     *
     * @var string
     */
    private $prefix = '[ {{H}}:{{i}}:{{s}} ][ {{log:level}} ][ {{caller:file}} ][ {{caller:info}} ] ';

    /**
     *
     * @var \Sys\Interfaces\User
     */
    private $user;

    /**
     *
     * @var string
     */
    private $sessionId = '';

    /**
     *
     * @var string
     */
    private $requestId = '';

    /**
     *
     * @var array
     */
    private $context = array();

    /**
     * $rootPath + / + $baseLogPath + / + $path + / + $filename
     *
     * @param string $rootPath
     * @param string $baseLogPath
     */
    public function __construct(string $rootPath, string $baseLogPath)
    {
        $this->rootPath = $rootPath;
        $this->baseLogPath = $baseLogPath;
    }

    /**
     *
     * @param \Sys\Interfaces\User $user
     */
    public function setUser(\Sys\Interfaces\User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param string $logLevel Psr log level
     * @throws \InvalidArgumentException
     */
    public function setLogLevel($logLevel = \Psr\Log\LogLevel::DEBUG)
    {
        if (!array_key_exists($logLevel, self::LOG_LEVELS)) {
            throw new \InvalidArgumentException();
        }

        $this->logLevel = $logLevel;
    }

    /**
     * Accept string which contain {{Y|m|d|H|s|user:id|user:username|caller:file|caller:info}}
     *
     * $rootPath + / + $baseLogPath + / + $path + / + $filename
     *
     * @param string $path directory in cui salvare i log (è possibile utilizzare delle variabili)
     */
    public function setPath(string $path)
    {
        $this->variablePath = $path;
    }

    /**
     * $rootPath + / + $baseLogPath + / + $path + / + $filename
     *
     * @param string $filename nome dei file in cui verranno salvati i log (è possibile utilizzare delle variabili)
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     *
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * 
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * 
     * @param string $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @throws \Exception
     */
    public function log($level, $message, array $context = array())
    {
        if (!array_key_exists($level, self::LOG_LEVELS)) {
            throw new \InvalidArgumentException('Not valid level');
        }

        if (self::LOG_LEVELS[$this->logLevel] >= self::LOG_LEVELS[$level]) {
            $this->makeContext($level);

            $path = $this->rootPath . '/' . $this->baseLogPath . '/' . $this->interpolate($this->variablePath);

            if (!is_dir($path)) {
                mkdir($path, 0770, true);
            }

            $file = fopen($path . '/' . $this->interpolate($this->filename), 'a+');

            if ($file) {
                fwrite($file, $this->interpolate($this->prefix) . $this->formatLogMessage($message, $context));

                fclose($file);
            }
        }
    }

    public function emergency($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(\Psr\Log\LogLevel::DEBUG, $message, $context);
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return string
     */
    private function interpolate(string $message, array $context = array()): string
    {
        $replace = array();

        // build a replacement array with braces around the context keys
        foreach ((count($context) > 0 ? $context : $this->context) as $key => $val) {

            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{{' . $key . '}}'] = (string) $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     *
     * @param string $level
     */
    private function makeContext(string $level)
    {
        $oDate = new \DateTime();

        $context = array(
            'Y' => $oDate->format('Y'),
            'm' => $oDate->format('m'),
            'd' => $oDate->format('d'),
            'H' => $oDate->format('H'),
            'i' => $oDate->format('i'),
            's' => $oDate->format('s'),
            'log:level' => strtoupper($level),
            'user:id' => $this->user instanceof \Sys\Interfaces\User ? $this->user->getId() : 'NULL',
            'user:username' => $this->user instanceof \Sys\Interfaces\User ? $this->user->getUsername() : 'NULL',
            'session:id' => $this->sessionId,
            'request:id' => $this->requestId,
        );

        $this->context = array_merge($context, $this->getCaller());
    }

    /**
     *
     * @return array
     */
    private function getCaller(): array
    {
        $callerObjects = debug_backtrace();

        $callerObject = null;

        do {
            $previousCallerObject = $callerObject;
            $callerObject = array_shift($callerObjects);
        } while (is_array($callerObject) && array_key_exists('class', $callerObject) && ($callerObject['class'] === self::class || $callerObject['class'] === \Log::class));

        $caller = array(
            'caller:info' => '',
            'caller:file' => ''
        );

        if (is_array($callerObject)) {
            if (array_key_exists('class', $callerObject)) {
                $caller['caller:info'] .= $callerObject['class'];

                if (array_key_exists('type', $callerObject)) {
                    $caller['caller:info'] .= $callerObject['type'];
                }
            }

            if (array_key_exists('function', $callerObject)) {
                $caller['caller:info'] .= $callerObject['function'];
            }
        }

        if (is_array($previousCallerObject)) {
            if (array_key_exists('file', $previousCallerObject)) {
                $caller['caller:file'] .= substr($previousCallerObject['file'], strlen($this->rootPath));

                if (array_key_exists('line', $previousCallerObject)) {
                    $caller['caller:file'] .= '::' . $previousCallerObject['line'];
                }
            }
        }

        return $caller;
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return string
     */
    private function formatLogMessage(string $message, array $context = array())
    {
        return implode(chr(13) . chr(10) . "\t", explode("\n", $this->interpolate($message, $context))) . chr(13) . chr(10);
    }

}
