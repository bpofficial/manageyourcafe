<?php

class token {
    private $token;

    function __construct($name, $store_id, $key) {
        $time = DateTime::createFromFormat("dHi");
        $this->token = hash("sha256", $name . $store_id . $_SERVER['REMOTE_ADDR']);
    }
    public function receipt() {
        return $this->token;
    }
}

?>