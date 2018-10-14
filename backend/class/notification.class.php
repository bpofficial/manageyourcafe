<?php

class notification {
    public $count = 0;
    private function increaseNotificationCount(string $type, string $name, string $store_id, string $element, int $count) {
        global $conn;//, $error;
        if ($type === "sidenav-bubble") {
            try {
                $st = $conn->prepare("SELECT `notifications` FROM `staff` WHERE `uname`='$name' AND `store_id`='$store_id'");
                $st->execute();
                $data = $st->fetchColumn();
            } catch (PDOException $e) {
                echo print_r($e);
            }
            if ($data != null || $data != "") {
                $data = json_decode($data,true);
                if ($data[$element] == null) {
                    $data[$element] = $count;
                    $this->count = $count;
                } else if ($data[$element] == "" || $data[$element] == null) {
                    $data[$element] = $count;
                } else if ($data[$element] == "0" || $data[$element] == 0) {
                    $data[$element] = $count;
                    $this->count = $count;
                } else if ((int)$data[$element] > 0) {
                    $data[$element] += (int)$count;
                    $this->count = $data[$element];
                }
            }
        } else {

        }
    }

    private function email(string $name, string $store_id, string $text) {
    }

    function __construct(string $type, string $name, string $store_id, string $element, int $count = 1) {
        if($type === "sidenav-bubble") {
            $this->increaseNotificationCount($type, $name, $store_id, $element, $count);
        } else if ($type === "email") {
            $this->email($name,$store_id,$element);
        }
    }
}

?>