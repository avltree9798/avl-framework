<?php
/**
 * Created by PhpStorm.
 * User: avltree
 * Date: 27/01/18
 * Time: 14:51
 */

class User extends Model
{
    protected $table = 'users';

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'role_id',
        'remember_token',
        'deleted_at',
        'created_at',
        'id'
    ];
}