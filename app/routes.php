<?php
    Router::get('/','MainController@index','www.index');
    Router::get('/lala', 'MainController@lala');
    Router::get('/admin', 'AdminController@index', 'admin.dashboard.index');
    Router::get('/admin/login', 'AdminController@login', 'admin.login');
    Router::post('/admin/do/login', 'AdminController@doLogin', 'admin.do.login');
    Router::get('/admin/logout', 'AdminController@doLogout', 'admin.do.logout');
?>