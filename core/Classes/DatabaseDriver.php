<?php
/**
 * Created by PhpStorm.
 * User: avltree
 * Date: 17/04/18
 * Time: 0:42
 */

abstract class DatabaseDriver
{
    /**
     * @var mysqli
     */
    protected $socket;

    /**
     * @var \mysqli_result|bool
     */
    protected $result;

    /**
     * @var int
     */
    public $current_field;

    /**
     * @var int
     */
    public $lengths;

    /**
     * @var int
     */
    public $num_rows;

    /**
     * @param string      $host
     * @param string      $username
     * @param string      $password
     * @param string|null $database
     * @return $this
     */
    public abstract function connect($host, $username, $password, $database = null);

    /**
     * @param string $database
     * @return $this
     */
    public abstract function changeDB($database);

    /**
     * @var array $arr
     * @return array
     */
    public abstract function refValues($arr = []);

    /**
     * @param string $query
     * @param array  $args
     * @return \mysqli_result|bool
     */
    public abstract function query($query, $args = null);

    public abstract function dataSeek($offset = 0);

    public abstract function fetchAll();

    public abstract function fetchArray();

    public abstract function fetchAssoc();

    public abstract function fetchFieldDirect($field);

    public abstract function fetchField();

    public abstract function fetchFields();

    public abstract function fetchObject($class_name = 'stdClass', $params = null);

    public abstract function fetchRow();

    public abstract function fieldSeek($field);

    public abstract function currentId();

    /**
     * @return array
     */
    public abstract function fetchAllKV();
}