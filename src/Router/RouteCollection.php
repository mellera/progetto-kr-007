<?php

namespace Sys\Router;

use Symfony\Component\Routing\Route;

class RouteCollection extends \Symfony\Component\Routing\RouteCollection
{

    /**
     * 
     * @param string $method
     * @param string $uri
     * @param string $actionClass
     */
    public function addSimpleRoute(string $method, string $uri, string $actionClass)
    {
        $this->addRoute($method, $uri, $actionClass);
    }

    /**
     * 
     * @param string $method
     * @param string $uri
     * @param string $actionClass
     * @param string $permissions
     */
    public function addProtectedRoute(string $method, string $uri, string $actionClass, string ...$permissions)
    {
        $this->addRoute($method, $uri, $actionClass, true, $permissions);
    }

    /**
     * 
     * @param string $method
     * @param string $uri
     * @param string $actionClass
     * @param bool $protected
     * @param array $permissions
     * @throws \InvalidArgumentException
     */
    private function addRoute(string $method, string $uri, string $actionClass, bool $protected = false, array $permissions = array())
    {
        if ($protected && count($permissions) === 0) {
            throw new \InvalidArgumentException('Missing permissions');
        }

        $defaults = self::createDefaults($actionClass, $permissions);

        parent::add($method . '::' . $uri, new Route($uri, $defaults, array(), array(), '', array(), [$method]));
    }

    /**
     * 
     * @param string $actionClass
     * @param array $permissions
     * @return array
     */
    private static function createDefaults(string $actionClass, array $permissions)
    {
        return array(
            '_controller' => $actionClass . '::execute',
            '__controller' => $actionClass,
            '__function' => 'execute',
            '_check_auth' => count($permissions) !== 0,
            '_required_permissions' => $permissions
        );
    }

}
