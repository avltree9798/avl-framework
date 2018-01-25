<?php

abstract class Middleware
{
    /**
     * @return bool
     */
    public abstract function handle();
}