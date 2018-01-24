<?php

class AdminController extends Controller
{
    public function index()
    {
        session_start();
        if ( ! Auth::user()) {
            URL::redirect(route('admin.login'));
        } else {
            dd(Auth::user());
        }
    }

    public function login()
    {
        View::load('admin::login');
    }

    public function doLogin()
    {
        $user = new User();
        Session::set('user', $user->auth(URL::post('email'), URL::post('password')), 10000000000);
        dd(json(Session::get('user')));
    }

    public function doLogout()
    {
        Session::endSession();
        URL::redirect(route('www.index'));
    }
}