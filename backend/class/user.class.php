<?php

class user {
    private function encrypt($text) {
        #region
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        return $salt . hash("sha256", $salt . $pass);
        #endregion
    }

    function __construct($name, $password, $email, $store_id) {
        #region
        global $conn, $LOG;
        $rights = "{\"post\":\"false\",\"level\":1,\"comment\":\"true\",\"edit\":\"false\",\"rosters\":\"false\"}";
        $settings = "{\"getEmails\":\"true\"}";
        $stmt = $conn->prepare("SELECT `uname` FROM `staff` WHERE `uname`='$name' AND `store_id`='$store_id'");
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {
            return false;
        } else {
            try {
                $password = $this->encrypt($password);
                $st = $conn->prepare("INSERT INTO `staff` (store_id,rights,uname,email,password,settings) VALUES ('$store_id','$rights','$name','$email','$password','$settings')");
                $st->execute();
                return true;
            } catch (PDOException $e) {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$LOG);
                return false;
            }
        }
        #endregion
    }

    function remove($user, $current_user, $store_id) {
        #region

        #endregion
    }

    function update(string $value, string $type, string $store_id, array $options = null) {
        #region

        #endregion
    }
}


?>