<?php
class MYSQLDatabaseDriver{
    /**
     * @var mysqli
     */
    protected $socket;

    /**
     * @var mixed|bool
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


    public function __construct()
    {
    }

    public function connect($host, $username, $password, $database = null)
    {

        if(is_null($database)){
            $this->socket = new mysqli($host, $username, $password);
        }else{
            $this->socket = new mysqli($host, $username, $password, $database);            
        }
    }

    /**
     * @return void
     */
    public function changeDB($database)
    {
        $this->socket->select_db($database);
    }

    /**
     * @var array $arr
     * @return array
     */
    public function refValues($arr = [])
    {
        if(strnatcmp(phpversion(), '5.3') >= 0)
        {
            $refs = [];
            foreach($arr as $k=>$v){
                $refs[$k] = &$arr[$k];
            }
            return $refs;
        }
        return $arr;
    }

    /**
     * @param string $query
     * @param array  $args
     * @return mixed|bool
     */
    public function query($query, $args = null)
    {
        if(is_null($args)){
            $this->result = $this->socket->query($query);
            $this->current_field = $this->result->current_field;
            $this->lengths = $this->result->lengths;
            $this->num_rows = $this->result->num_rows;
            return $this->result;
        }else{
            if(!is_array($args)){
                $args = [$args];
            }
            if($stmt = $this->socket->prepare($query)){
                $datatypes = '';
                foreach($args as $value){
                    if(is_int($value)){
                        $datatypes .= 'i';
                    }else if(is_double($value)){
                        $datatypes .= 'd';
                    }else if(is_string($value)){
                        $datatypes .= 's';
                    }else{
                        $datatypes .= 'b';
                    }
                }
                array_unshift($args, $datatypes);
                if(call_user_func_array([$stmt,'bind_param'], $this->refValues($args))){
                    $stmt->execute();
                    $this->result = $stmt->get_result();
                    if($this->result){
                        $this->current_field = $this->result->current_field;
                        $this->lengths = $this->result->lengths;
                        $this->num_rows = $this->result->num_rows;
                    }else{
                        $this->current_field = '';
                        $this->num_rows = 0;
                        $this->lengths = 0;
                    }
                    $this->error = $stmt->error;
                    return $this->result;                    
                }else{
                    $this->current_field = '';
                    $this->num_rows = 0;
                    $this->lengths = 0;
                    return false;
                }
            }else{
                $this->current_field = '';
                $this->num_rows = 0;
                $this->lengths = 0;

            }
        }
    }

    public function dataSeek($offset = 0)
    {
        return $this->result->data_seek($offset);
    }

    public function fetchAll()
    {
        return $this->result->fetch_all();
    }

    public function fetchArray()
    {
        return $this->result->fetch_array();
    }

    public function fetchAssoc()
    {
        return $this->result->fetch_assoc();
    }

    public function fetchFieldDirect($field)
    {
        return $this->result->fetch_field_direct($field);
    }

    public function fetchField()
    {
        return $this->result->fetch_field();
    }

    public function fetchFields()
    {
        return $this->result->fetch_fields();
    }

    public function fetchObject($class_name = 'stdClass', $params = null)
    {
        if(is_null($params)){
            return $this->result->fetch_object($class_name);
        }else{
            return $this->result->fetch_object($class_name, $params);
        }
    }

    public function fetchRow()
    {
        return $this->result->fetch_row();
    }

    public function fieldSeek($field)
    {
        return $this->result->field_seek($field);
    }

    public function currentId()
    {
        return $this->result->insert_id;
    }

    /**
     * @return array
     */
    public function fetchAllKV()
    {
        $out = [];
        while($row = $this->result->fetch_assoc()){
            $out[] = $row;
        }
        return $out;
    }

    public function __destruct(){
        if(isset($this->socket) && isset($this->result)){
            $this->socket->close();
        }
    }
}
?>