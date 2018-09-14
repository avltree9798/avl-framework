<?php
/*
 *
 * Router::get/post (URLPattern, Handler, Name(Optional))
 * For passing a variable, you can use {}...
 * for example
 * Router::get('/{test}','MainController@passVar','www.test');
 * Handler can be a callback function or a string containing ClassName@handlerMethod
 *
 */
Router::get('/','HomeController@index','www.index');
Router::get('/test', function(){
    return Response::json(User::all());
});