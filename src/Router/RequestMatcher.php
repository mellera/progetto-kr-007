<?php

namespace Sys\Router;

class RequestMatcher implements \Symfony\Component\Routing\Matcher\RequestMatcherInterface
{

    const REQUIREMENT_MATCH = 0;
    const REQUIREMENT_MISMATCH = 1;
    const ROUTE_MATCH = 2;

    protected $request;
    protected $allow = array();
    protected $routes;

    /**
     * 
     * @param \Symfony\Component\Routing\RouteCollection $routes
     */
    public function __construct(\Symfony\Component\Routing\RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws \Symfony\Component\Routing\Exception\NoConfigurationException
     * @throws \Symfony\Component\Routing\Exception\MethodNotAllowedException
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function matchRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;

        $pathinfo = $request->getPathInfo();

        $this->allow = array();

        if ($ret = $this->matchCollection(rawurldecode($pathinfo))) {
            $this->request = null;

            return $ret;
        }

        if ('/' === $pathinfo && !$this->allow) {
            throw new \Symfony\Component\Routing\Exception\NoConfigurationException();
        }

        if (count($this->allow) > 0) {
            $ex = new \Symfony\Component\Routing\Exception\MethodNotAllowedException(array_unique($this->allow));
        } else {
            $ex = new \Symfony\Component\Routing\Exception\ResourceNotFoundException(sprintf('No routes found for "%s".', $pathinfo));
        }

        throw $ex;
    }

    /**
     * Tries to match a URL with a set of routes.
     * 
     * @param string $pathinfo The path info to be parsed
     * @return array|null
     */
    protected function matchCollection($pathinfo)
    {
        foreach ($this->routes as $name => $route) {
            $compiledRoute = $route->compile();

            // check the static prefix of the URL first. Only use the more expensive preg_match when it matches
            if ('' !== $compiledRoute->getStaticPrefix() && 0 !== strpos($pathinfo, $compiledRoute->getStaticPrefix())) {
                continue;
            }

            if (!preg_match($compiledRoute->getRegex(), $pathinfo, $matches)) {
                continue;
            }

            $hostMatches = array();
            if ($compiledRoute->getHostRegex() && !preg_match($compiledRoute->getHostRegex(), $this->request->getHost(), $hostMatches)) {
                continue;
            }

            $status = $route->getSchemes() && !$route->hasScheme($this->request->getScheme()) ? self::REQUIREMENT_MISMATCH : self::REQUIREMENT_MATCH;

            if (self::REQUIREMENT_MISMATCH === $status) {
                continue;
            }

            // check HTTP method requirement
            if ($requiredMethods = $route->getMethods()) {
                // HEAD and GET are equivalent as per RFC
                if ('HEAD' === $method = $this->request->getMethod()) {
                    $method = 'GET';
                }

                if (!in_array($method, $requiredMethods)) {
                    if (self::REQUIREMENT_MATCH === $status) {
                        $this->allow = array_merge($this->allow, $requiredMethods);
                    }

                    continue;
                }
            }

            return $this->getAttributes($route, $name, array_replace($matches, $hostMatches));
        }
    }

    /**
     * Returns an array of values to use as request attributes.
     * 
     * @param \Symfony\Component\Routing\Route $route The route we are matching against
     * @param string $name The name of the route
     * @param array $attributes An array of attributes from the matcher
     * @return array An array of parameters
     */
    protected function getAttributes(\Symfony\Component\Routing\Route $route, $name, array $attributes)
    {
        $attributes['_route'] = $name;

        return $this->mergeDefaults($attributes, $route->getDefaults());
    }

    /**
     * Get merged default parameters.
     * 
     * @param array $params The parameters
     * @param array $defaults The defaults
     * @return array Merged default parameters
     */
    protected function mergeDefaults($params, $defaults)
    {
        foreach ($params as $key => $value) {
            if (!is_int($key)) {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

}
