<?php

class User extends Model
{

    protected $table = 'users';

    /**
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected function sales()
    {
        return $this->hasMany(Sales::class, 'cashier_id');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getSales()
    {
        return $this->sales;
    }
}