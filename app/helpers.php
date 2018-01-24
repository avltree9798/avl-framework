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
     * @return string
     */
    function route($route = '')
    {
        foreach ($GLOBALS['config']['routes'] as $r) {
            if ($r['name'] == $route) {
                return $GLOBALS['config']['domain'] . $r['url'];
            }
        }

        return new RouteNotFound(404, $route);
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