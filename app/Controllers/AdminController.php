<?php

class AdminController extends Controller
{
    public function index()
    {
        session_start();
        if ( ! Auth::user()) {
            Request::redirect(route('admin.login'));
        } else {
            dd(Request::only([
                'ken'
            ]));
        }
    }

    public function login()
    {
        View::load('admin.login');
    }

    public function doLogin()
    {
        $user = new User();
        Session::set('user', $user->auth(Request::post('email'), Request::post('password')), 10000000000);
        dd(json(Session::get('user')));
    }

    public function doLogout()
    {
        Session::endSession();
        Request::redirect(route('www.index'));
    }
}