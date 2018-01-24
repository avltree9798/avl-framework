<?php

class Model
{

    /**
     * @var MYSQLDatabaseDriver
     */
    public $model;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $hidden;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->model = new MYSQLDatabaseDriver();
        $this->model->connect($GLOBALS['config']['database']['hostname'], $GLOBALS['config']['database']['username'],
            $GLOBALS['config']['database']['password'], $GLOBALS['config']['database']['database']);
    }
}