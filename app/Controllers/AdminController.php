<?php

class AdminController extends Controller
{
    public function index()
    {
        session_start();
        if ( ! Auth::user()) {
            Request::redirect(route('admin.login'));
        } else {
            dd(Auth::user());
        }
    }

    public function login()
    {
        View::load('admin.login');
    }

    public function doLogin()
    {
        $user = Auth::attempt(Request::post('email'), Request::post('password'));
        Session::set('user', $user, 10000000000);
        dd(Session::get('user'));
    }

    public function doLogout()
    {
        Session::endSession();
        Request::redirect(route('www.index'));
    }
}