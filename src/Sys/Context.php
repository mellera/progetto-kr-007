<?php

namespace Sys;

class Context
{

    /**
     *
     * @var \Sys\Config
     */
    private static $config;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    private static $logger;

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private static $em;

    /**
     *
     * @param \Sys\Config $config
     */
    public static function setConfig(\Sys\Config $config)
    {
        static::$config = $config;
    }

    /**
     *
     * @return \Sys\Config
     */
    public static function config(): \Sys\Config
    {
        return static::$config;
    }

    /**
     * @return \Sys\Logger\Logger
     */
    public static function logger(): \Sys\Logger\Logger
    {
        if (!(static::$logger instanceof \Sys\Logger\Logger)) {
            static::$logger = new \Sys\Logger\Logger(self::config()->getRootPath(), self::config()->getLoggerBasePath());

            static::$logger->setPath(self::config()->getLoggerPath());
            static::$logger->setFilename(self::config()->getLoggerFilename());
            static::$logger->setPrefix(self::config()->getLoggerPrefix());
            static::$logger->setLogLevel(self::config()->getLogLevel());
        }

        return static::$logger;
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public static function em(): \Doctrine\ORM\EntityManager
    {
        if (!(static::$em instanceof \Doctrine\ORM\EntityManager)) {
            // Create a simple "default" Doctrine ORM configuration for Annotation Mapping
            $isDevMode = true;

            $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(self::config()->getRootPath() . "/src/Model"), $isDevMode);

            $config->setSQLLogger(new \Sys\Logger\SQLLogger(self::logger()));

            $driver = getenv('DB_DRIVER');

            if ($driver === 'sqlite') {
                $conn = array(
                    'driver' => 'pdo_sqlite',
                    'path' => self::config()->getRootPath() . getenv('DB_PATH'),
                );
            } elseif ($driver === 'mysql') {
                $conn = array(
                    'driver' => 'pdo_mysql',
                    'user' => getenv('DB_USER'),
                    'password' => getenv('DB_PASS'),
                    'host' => getenv('DB_HOST'),
                    'port' => getenv('DB_PORT'),
                    'dbname' => getenv('DB_NAME'),
                    'charset' => getenv('DB_CHARSET')
                );
            }

            // obtaining the entity manager
            static::$em = \Doctrine\ORM\EntityManager::create($conn, $config);
        }

        return static::$em;
    }

    public static function getPhinxConnection()
    {
        $driver = getenv('DB_DRIVER');

        $conn = array();

        if ($driver === 'sqlite') {
            $conn = array(
                'adapter' => 'sqlite',
                'name' => self::config()->getRootPath() . getenv('DB_PATH') // , 'suffix' => ''
            );
        } elseif ($driver === 'mysql') {
            $conn = array(
                'adapter' => 'mysql',
                'host' => getenv('DB_HOST'),
                'name' => getenv('DB_NAME'),
                'user' => getenv('DB_USER'),
                'pass' => getenv('DB_PASS'),
                'port' => getenv('DB_PORT'),
                'charset' => getenv('DB_CHARSET')
            );
        }

        return $conn;
    }

    /**
     * 
     * @param \Throwable $ex
     */
    public static function handleException(\Throwable $ex)
    {
        self::config()->handleException($ex);
    }

    /**
     * 
     * @return string
     */
    public static function getAppKey(): string
    {
        return getenv('APP_KEY');
    }

}
