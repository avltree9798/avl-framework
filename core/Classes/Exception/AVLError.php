<?php

class AVLError
{
    /**
     * @var int
     */
    protected $type;

    /**
     * AVLError constructor.
     *
     * @param int $type
     */
    public function __construct($type)
    {
        $this->type = $type;
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