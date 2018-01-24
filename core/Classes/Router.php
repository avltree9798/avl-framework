<?php

class Router
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * @var array
     */
    protected $params;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->routes = $GLOBALS['config']['routes'];
        $route = $this->findRoute();
        $method = $route['method'];
        if (class_exists($route['controller'])) {
            $controller = new $route['controller']();
            if (method_exists($controller, $method)) {
                if ($route['type'] === $_SERVER['REQUEST_METHOD']) {
                    if (isset($this->params)) {
                        call_user_func_array([$controller, $method], $this->params);
                    } else {
                        $controller->$method();
                    }
                } else {
                    new RouterMethodForbidden(403);
                }
            } else {
                new MethodNotFound(404);
            }
        } else {
            new ClassNotFound(404);
        }
    }

    /**
     * @param string $part
     * @return string
     */
    public static function uri($part)
    {
        $parts = explode("/", $_SERVER["REQUEST_URI"]);

        return (isset($parts[$part])) ? $parts[$part] : '';
    }

    /**
     * @param string $uri
     * @param string $controller
     * @param string $type
     */
    private static function pushRoute($uri, $controller, $name, $type)
    {
        $controller = explode('@', $controller);
        $class = $controller[0];
        $method = $controller[1];
        $GLOBALS['config']['routes'][] = [
            'url'        => $uri,
            'controller' => $class,
            'method'     => $method,
            'type'       => $type,
            'name'       => $name
        ];
    }

    /**
     * @param string $uri
     * @param string $controller
     * @param string $name
     */
    public static function get($uri, $controller, $name = '')
    {
        self::pushRoute($uri, $controller, $name, 'GET');
    }

    /**
     * @param string $uri
     * @param string $controller
     * @param string $name
     */
    public static function post($uri, $controller, $name)
    {
        self::pushRoute($uri, $controller, $name, 'POST');
    }

    /**
     * @param string|array $route
     * @return array
     */
    protected function routerPart($route)
    {
        if (is_array($route)) {
            $route = $route["url"];
        }

        return explode("/", $route);
    }

    /**
     * @return array
     */
    protected function findRoute()
    {
        foreach ($this->routes as $route) {
            $parts = $this->routerPart($route);
            $allUri = implode('',$this->routerPart($_SERVER['REQUEST_URI']));
            $allPart = '';
            foreach ($parts as $key => $part) {
                if ($part != "*") {
                    preg_match('/{[^\s]+}/', $part, $matches);
                    if ($matches) {
                        $this->params[] = Router::uri($key);
                        $allPart .= $part;
                    } else {
                        $allPart .= $part;
                    }
                }
            }
            if ($allPart == $allUri) {
                return $route;
            }
        }
        return new RouteNotFound(404, isset($route) ? $route['url'] : $_SERVER['REQUEST_URI']);
    }
}