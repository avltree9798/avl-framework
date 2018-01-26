<?php
/**
 * Created by PhpStorm.
 * User: avltree
 * Date: 26/01/18
 * Time: 2:59
 */

class Sales extends Model
{
    /**
     * @var string
     */
    protected $table = 'sales';

    protected function user()
    {
        return $this->belongsTo(User::class,'cashier_id');
    }

    public function getUser()
    {
        return $this->user;
    }
}