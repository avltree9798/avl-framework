<?php
if ( ! function_exists('dd')) {
    /**
     * @param mixed $mixed
     */
    function dd($mixed)
    {
        var_dump($mixed);
        die();
    }
}
if ( ! function_exists('assets')) {
    /**
     * @param string $asset
     * @return string
     */
    function assets($asset = '')
    {
        return $GLOBALS['config']['domain'] . '/public/' . $asset;
    }
}
if ( ! function_exists('route')) {
    /**
     * @param string $route
     * @param array  $params
     * @return string
     */
    function route($route = '', $params = [])
    {
        foreach ($GLOBALS['config']['routes'] as $r) {
            if ($r['name'] == $route) {
                $url = explode('/', $r['url']);
                $uri = '';
                $index = 0;
                foreach ($url as $u) {
                    if($u==='')continue;
                    preg_match('/{[^\s]+}/', $u, $matches);
                    if ($matches) {
                        $uri .= '/'.$params[$index++];
                    } else {
                        $uri .=  '/'.$u;
                    }
                }

                return $GLOBALS['config']['domain'] . $uri;
            }
        }

        $error = new RouteNotFound(404, $route);
        $error->show();
        return null;
    }
}

if ( ! function_exists('json')) {
    /**
     * @param mixed $json
     * @return mixed
     */
    function json($json)
    {
        return json_decode(json_encode($json));
    }
}

if ( ! function_exists('abort')) {
    /**
     * @param int    $status
     * @param string $message
     */
    function abort($status, $message)
    {
        echo $message;
        http_response_code($status);
        die($status);
    }
}

if ( ! function_exists('csrf_token')) {

    /**
     * @return string
     */
    function csrf_token()
    {
        return Session::get('csrf');
    }
}

if ( ! function_exists('csrf_field')) {

    /**
     * @return string
     */
    function csrf_field()
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if ( ! function_exists('e')) {
    /**
     * @param string $output
     */
    function e($output)
    {
        echo htmlentities($output);
    }
}

if ( ! function_exists('generate_csrf_token')) {
    function generate_csrf_token()
    {
        srand(time());
        $data = [
            'key' => bin2hex(random_bytes(32)),
            'iv'  => rand(0, 10101010)
        ];
        Session::set('csrf', base64_encode(json_encode($data)));
    }
}

if ( ! function_exists('cast')) {
    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object        $sourceObject
     * @return object
     */
    function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }

        return $destination;
    }
}