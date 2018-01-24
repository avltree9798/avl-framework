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

Router::post('/admin/login', 'AdminController@doLogin', 'admin.do.login');
Router::get('/admin/logout', 'AdminController@doLogout', 'admin.do.logout');