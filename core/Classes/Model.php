<?php

class Model
{
    private static $query;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    protected static $wheres;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        self::$query = new MYSQLDatabaseDriver();
        self::$query->connect($GLOBALS['config']['database']['hostname'], $GLOBALS['config']['database']['username'],
            $GLOBALS['config']['database']['password'], $GLOBALS['config']['database']['database']);
        Session::set('class-' . static::class, $this);
        $this->boot();
    }

    public function boot()
    {
        if (isset($this->id)) {
            $methods = get_class_methods(static::class);
            foreach ($methods as $method) {
                if (substr($method, 0, 3) == 'get') {
                    $call = substr($method, 3, strlen($method) - 3);
                    $call = lcfirst(ucwords($call));
                    if (method_exists($this, $call)) {
                        $this->$call();
                    }
                }
            }
        }
    }

    /**
     * @param int $id
     * @return object|\stdClass
     */
    public static function find($id)
    {
        /**
         * @var \Model $model
         */
        $model = Session::get('class-' . static::class);
        self::$query->query('SELECT * FROM `' . $model->table . '` WHERE `id` = ?', [
            $id
        ]);
        $data = self::$query->fetchAllKV()[0];
        $data = self::makeObject($data);

        return (self::$query->num_rows === 0) ? null : $data;
    }

    /**
     * @param array  $array
     * @param string $className
     * @return object
     */
    private static function makeObject($array = [], $className = 'stdClass')
    {
        /**
         * @var \Model $object
         */
        $object = new $className();
        foreach ($array as $key => $value) {
            if ( ! in_array($key, $object->hidden)) {
                $object->$key = $value;
            }
        }
        $object->boot();

        return $object;
    }

    /**
     * @param array  $data
     * @param string $relation
     * @return array
     */
    private static function makeArrayOfObject($data, $relation = null)
    {
        if ( ! $relation) {
            $relation = static::class;
        }
        $return = [];
        foreach ($data as $d) {
            $return[] = self::makeObject($d, $relation);
        }

        return $return;
    }

    /**
     * @param string $relation
     * @param string $foreignKey
     * @param string $primaryKey
     * @return array
     */
    protected function hasMany($relation = '', $foreignKey = '', $primaryKey = 'id')
    {
        $propsName = debug_backtrace()[1]['function'];
        if ( ! isset($this->$propsName)) {
            /**
             * @var \Model $class
             */
            $class = new $relation();
            $query = 'SELECT `' . $class->table . '`.* FROM `' . $this->table . '` JOIN `' . $class->table . '` ON `' . $this->table . '`.`' . $primaryKey . '` = `' . $class->table . '`.`' . $foreignKey . '` WHERE `' . $this->table . '`.`' . $primaryKey . '` = ?';
            self::$query->query($query, [
                $this->$primaryKey
            ]);
            $data = self::$query->fetchAllKV();
            $propsName = debug_backtrace()[1]['function'];
            $this->$propsName = self::makeArrayOfObject($data, $relation);
        }

        return $this->$propsName;
    }

    /**
     * @param string $relation
     * @param string $foreignKey
     * @param string $primaryKey
     * @return object|\stdClass
     */
    protected function hasOne($relation = '', $foreignKey = '', $primaryKey = 'id')
    {
        $propsName = debug_backtrace()[1]['function'];
        if ( ! isset($this->$propsName)) {
            /**
             * @var \Model $class
             */
            $class = new $relation();
            $query = 'SELECT `' . $class->table . '`.* FROM `' . $this->table . '` JOIN `' . $class->table . '` ON `' . $this->table . '`.`' . $primaryKey . '` = `' . $class->table . '`.`' . $foreignKey . '` WHERE `' . $this->table . '`.`' . $primaryKey . '` = ?';
            self::$query->query($query, [
                $this->$primaryKey
            ]);
            $data = self::$query->fetchAllKV()[0];
            $data = self::makeObject($data);
            $this->$propsName = $data;
        }

        return $this->$propsName;
    }

    /**
     * @return array
     */
    public static function all()
    {
        /**
         * @var \Model $model
         */
        $model = Session::get('class-' . static::class);
        self::$query->query('SELECT * FROM `' . $model->table . '`');
        $data = self::$query->fetchAllKV();
        $data = self::makeArrayOfObject($data);

        return (self::$query->num_rows === 0) ? null : $data;
    }

    /**
     * @param string $column
     * @param string $value
     * @return static
     */
    public static function where($column = '', $value = '')
    {
        self::$wheres[$column] = $value;

        return new static();
    }

    /**
     * @return null|\Model[]|\stdClass[]
     */
    public static function execute()
    {
        $model = Session::get('class-' . static::class);
        $query = 'SELECT * FROM `' . $model->table . '` WHERE';
        $where = [];
        foreach (self::$wheres as $col => $val) {
            $query .= ' ' . $col . ' = ? AND';
            $where[] = $val;
        }
        self::$wheres = [];
        $query = substr($query, 0, -4);
        self::$query->query($query, $where);
        $data = self::$query->fetchAllKV();
        $data = self::makeArrayOfObject($data);

        return $data;
    }

}