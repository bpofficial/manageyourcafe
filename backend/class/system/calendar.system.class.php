<?php
class calendar extends system {
    
    public function __construct() {
        system::__construct($_SESSION);
    }

    public function build() {
        global $conn, $error;
        $time = microtime(true);
        $success = true;
        $ret = "";

        //TODO: ALL OF IT

        return json_encode(array(
            "success" => $success,
            "errors" => $error->generate(),
            "value" => base64_encode($ret),
            "time" => microtime(true) - $time
        ));
    }
}

?>