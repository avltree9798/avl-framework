<?php
class RouterMethodForbidden extends AVLError{
    /**
     * @inheritdoc
     */
    public function show()
    {
        http_response_code($this->type);
        echo "Router method forbidden";
        die();        
    }
}
?>