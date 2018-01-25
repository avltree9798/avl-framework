<?php
/*
 *
 * Router::get/post (URLPattern, Controller@Method, Name(Optional))
 * For passing a variable, you can use {}...
 * for example
 * Router::get('/{test}','MainController@passVar','www.test');
 *
 *
 */
Router::get('/', 'MainController@index', 'www.index');
Router::get('/lala', 'MainController@lala');
Router::get('/admin', 'AdminController@index', 'admin.dashboard.index');
Router::get('/admin/login', 'AdminController@login', 'admin.login');
Router::post('/admin/login', 'AdminController@doLogin', 'admin.do.login');
Router::get('/admin/logout', 'AdminController@doLogout', 'admin.do.logout');