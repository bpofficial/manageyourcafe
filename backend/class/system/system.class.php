<?php
class system {
    public $user, $store_id, $priv;
    public $error_page = <<<HTML
        <div>
            Had some trouble :c
        </div>
HTML;

    public function __construct($session) {
        global $error;
        $error->add_error("%cConstructing system.", ['color:black;'], true);
        if(!empty($session)) {
            (isset($session)) ? ((session_check('store_id', $session)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
            $this->user = $session['uname'];
            $this->store_id = $session['store_id'];
            $this->priv = $session['priv'];
        } else {
            (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
            $this->user = $_SESSION['uname'];
            $this->store_id = $_SESSION['store_id'];
            $this->priv = $_SESSION['priv'];
        }
    }

    public function humanTiming($time) {
        #region
        $time = time() - strtotime($time);
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => ' yrs',
            2592000 => ' mths',
            604800 => ' wks',
            86400 => ' days',
            3600 => 'hrs',
            60 => 'm',
            1 => 's'
        );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.''.$text;
        }
        #endregion
    }
}

?>