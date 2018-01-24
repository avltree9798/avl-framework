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

    public function passVar($lala, $testong)
    {
        echo 'Halo '.$lala.' '.$testong.', nice too meet you ^_^!';
    }
}