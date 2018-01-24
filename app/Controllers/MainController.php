<?php

class MainController extends Controller
{
    public function index()
    {
        $name = Session::get('name');
        $data = [
            'text' => 'Hello ' . $name
        ];
        View::load('main::index', $data);
    }

    public function lala()
    {
        dd('Lala');
    }
}