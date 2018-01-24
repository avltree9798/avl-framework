<?php
class MethodNotFound extends AVLError{
    /**
     * @inheritdoc
     */
    public function show()
    {
        http_response_code($this->type);
        echo "Method {$this->type} error";
        die();        
    }
}
?>