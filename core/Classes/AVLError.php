<?php
class AVLError{   
    /**
     * @var int
     */
    protected $type;

    /**
     * Error Class Constructor.
     * 
     * @param int $type
     */
    public function __construct($type)
    {
        $this->type = $type;
        $this->show();
    }

    /**
     * @return void
     */
    public function show()
    {
        http_response_code($this->type);
        echo "{$this->type} error";
        die();        
    }
}
?>