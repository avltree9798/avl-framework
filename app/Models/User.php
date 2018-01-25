<?php

class User extends Model
{

    protected $table = 'users';

    public function auth($email, $password)
    {
        $this->getModel()->query('SELECT * FROM `users` WHERE `email` = ? AND `password` = ?', [
            $email,
            $password
        ]);
        if ($row = $this->getModel()->fetchObject(User::class)) {
            return $row;
        } else {
            return [];
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}