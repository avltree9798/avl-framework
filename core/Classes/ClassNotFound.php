<?php
class ClassNotFound extends AVLError{
    /**
     * @inheritdoc
     */
    public function show()
    {
        http_response_code($this->type);
        echo "Class {$this->type} error";
        die();        
    }
}
?>