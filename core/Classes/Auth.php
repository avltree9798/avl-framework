<?php

class Auth
{
    public static function user()
    {
        return (Session::check('user')) ? Session::get('user') : false;
    }
}