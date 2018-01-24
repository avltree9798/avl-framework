<?php
class RouteNotFound extends AVLError{
    /**
     * @var string
     */
    protected $route;

    /**
     * RouteNotFound Class Constructor.
     * 
     * @param int    $type
     * @param string $route
     */
    public function __construct($type, $route){
        $this->route = $route;
        parent::__construct($type);
    }

    public function show(){
        http_response_code($this->type);
        echo "Route {$this->type} error";
        die();       
    }
}
?>