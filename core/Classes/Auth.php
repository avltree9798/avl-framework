<?php

class Auth
{
    /**
     * @var string
     */
    protected static $model = User::class;

    protected static $check = [
        'email',
        'password'
    ];

    /**
     * @return bool|\User
     */
    public static function user()
    {
        return (Session::check('user')) ? Session::get('user') : false;
    }

    /**
     * @param string $email
     * @param string $password
     * @return \Model
     */
    public static function attempt($email, $password)
    {
        $model = self::$model;
        $check = self::$check;
        $result = $model::where($check[0], $email)->where($check[1], $password)->execute()[0];
        return $result;
    }

    /**
     * @param int $id
     * @return bool|\User
     */
    public static function loginUsingId($id)
    {
        $user = User::find($id);
        if ($user) {
            Session::set('user', $user);
        }

        return Auth::user();
    }
}