<?php

class Model
{
    /**
     * @var \MYSQLDatabaseDriver
     */
    private static $query;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array
     */
    protected static $wheres = [];

    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model constructor.
     */
    public function __construct()
    {
        self::$query = new MYSQLDatabaseDriver();
        self::$query->connect($GLOBALS['config']['database']['hostname'], $GLOBALS['config']['database']['username'],
            $GLOBALS['config']['database']['password'], $GLOBALS['config']['database']['database']);
        Session::set('class-' . static::class, $this);
    }

    /**
     * @param string $prop
     * @return null
     */
    public function __get($prop)
    {
        if (isset($this->id)) {
            if (method_exists($this, $prop)) {
                $this->$prop = $this->$prop();

                return $this->$prop;
            } else {
                return null;
            }
        } else {
            return null;
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
        $class = static::class;
        $model = new $class();
        self::$query->query('SELECT * FROM `' . $model->table . '` WHERE `id` = ?', [
            $id
        ]);
        $data = self::$query->fetchAllKV();
        if (count($data)) {
            $data = self::makeObject($data[0], $class);
        } else {
            $data = null;
        }

        return $data;
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
            if (array_key_exists($key, $object->casts)) {
                switch ($object->casts[$key]) {
                    case 'int':
                        $value = (int) $value;
                        break;
                    case 'double':
                        $value = (double) $value;
                        break;
                    case 'float':
                        $value = (float) $value;
                        break;
                    case 'bool':
                        $value = (bool) $value;
                        break;
                }
            }
            $object->$key = $value;
        }

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
        /**
         * @var \Model $class
         */
        $class = new $relation();
        $query = 'SELECT `' . $class->table . '`.* FROM `' . $this->table . '` JOIN `' . $class->table . '` ON `' . $this->table . '`.`' . $primaryKey . '` = `' . $class->table . '`.`' . $foreignKey . '` WHERE `' . $this->table . '`.`' . $primaryKey . '` = ?';
        self::$query->query($query, [
            $this->$primaryKey
        ]);
        $data = self::$query->fetchAllKV();

        return self::makeArrayOfObject($data, $relation);
    }

    /**
     * @param string $relation
     * @param string $foreignKey
     * @param string $primaryKey
     * @return object|\stdClass
     */
    protected function hasOne($relation = '', $foreignKey = '', $primaryKey = 'id')
    {
        /**
         * @var \Model $class
         */
        $class = new $relation();
        $query = 'SELECT `' . $class->table . '`.* FROM `' . $this->table . '` JOIN `' . $class->table . '` ON `' . $this->table . '`.`' . $primaryKey . '` = `' . $class->table . '`.`' . $foreignKey . '` WHERE `' . $this->table . '`.`' . $primaryKey . '` = ?';
        self::$query->query($query, [
            $this->$primaryKey
        ]);
        $data = self::$query->fetchAllKV();
        if (count($data)) {
            $data = self::makeObject($data[0], $relation);
        } else {
            $data = null;
        }

        return $data;
    }

    /**
     * @param string $relation
     * @param string $foreignKey
     * @param string $primaryKey
     * @return object|\stdClass
     */
    protected function belongsTo($relation = '', $foreignKey = '', $primaryKey = 'id')
    {
        /**
         * @var \Model $class
         */
        $class = new $relation();
        $query = 'SELECT `' . $class->table . '`.* FROM `' . $this->table . '` JOIN `' . $class->table . '` ON `' . $class->table . '`.`' . $primaryKey . '` = `' . $this->table . '`.`' . $foreignKey . '` WHERE `' . $class->table . '`.`' . $primaryKey . '` = ?';
        self::$query->query($query, [

            $this->$foreignKey
        ]);
        $data = self::$query->fetchAllKV();
        if (count($data)) {
            $data = self::makeObject($data[0], $relation);
        } else {
            $data = null;
        }

        return $data;
    }

    /**
     * @return array
     */
    public static function all()
    {
        /**
         * @var \Model $model
         */
        $class = static::class;
        $model = new $class();
        self::$query->query('SELECT * FROM `' . $model->table . '`');
        $data = self::$query->fetchAllKV();
        $data = self::makeArrayOfObject($data, $model);

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
        $class = static::class;
        $model = new $class();
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
        $data = self::makeArrayOfObject($data, $model);

        return $data;
    }

    public static function create($properties)
    {
        /**
         * @var \Model $obj
         */
        $class = static::class;
        $obj = new $class();
        foreach ($properties as $key => $value) {
            if (in_array($key, $obj->fillable)) {
                $obj->$key = $value;
            }
        }

        return $obj;
    }

    /**
     * @return object|\stdClass
     */
    public function save()
    {
        $fillable = $this->fillable;
        if (count($fillable)) {
            $params = [];
            $id = -1;
            if (property_exists($this, $this->primaryKey)) {
                $id = $this->{$this->primaryKey};
                $query = "UPDATE `{$this->table}` SET";
                foreach ($fillable as $f) {
                    $query .= " `{$f}` = ?, ";
                    $params[] = $this->$f;
                }
                $query = substr($query, 0, -2);
                $query .= " WHERE `{$this->primaryKey}` = ?";
                $params[] = $this->{$this->primaryKey};
            } else {
                $query = "INSERT INTO `{$this->table}` ({$this->primaryKey},";
                $values = "(NULL,";
                foreach ($fillable as $f) {
                    $query .= "{$f},";
                    $params[] = $this->$f;
                    $values .= "?,";
                }
                $values = substr($values, 0, -1);
                $query = substr($query, 0, -1);
                $query .= ')';
                $values .= ')';
                $query = $query . ' VALUES ' . $values;
            }

            $queryBuilder = self::$query;
            $queryBuilder->query($query, $params);
            $id = $id === -1 ? $queryBuilder->currentId() : $id;

            return static::find($id);
        }
    }

    /**
     * @return $this
     */
    public function delete()
    {
        if (isset($this->{$this->primaryKey})) {
            $queryBuilder = self::$query;
            $query = "DELETE FROM `{$this->table}` WHERE `{$this->table}`.`{$this->primaryKey}` = ?";
            $params = [
                $this->{$this->primaryKey}
            ];
            $queryBuilder->query($query, $params);
        }

        return $this;
    }

}