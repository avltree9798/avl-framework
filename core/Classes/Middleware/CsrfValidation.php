<?php

class CsrfValidation extends Middleware
{

    /**
     * @return bool
     */
    public function handle()
    {
        if (hash_equals(Request::post('csrf_token'), Session::get('csrf'))) {
            return true;
        } else {
            abort(403, 'CSRF token validation failed ');
        }
    }
}