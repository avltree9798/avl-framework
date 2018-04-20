<?php
/**
 * Created by PhpStorm.
 * User: avltree
 * Date: 14/02/18
 * Time: 1:33
 */

class HomeController extends Controller
{
    public function index()
    {
        $name = 'Anthony Viriya';
        return View::load('welcome',compact('name'));
    }
}