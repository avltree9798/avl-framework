<?php

class Controller
{
    public function __construct()
    {
        $GLOBALS['instances'][] = &$this;
    }
}