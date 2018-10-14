<?php
class errorHandle {
    #region -- Private variables --
    private $error_text = "";
    private $error_css = array();
    #endregion

    public function add_error(string $text, array $css = null, bool $nl = true) {
        #region
        $text = str_replace("'", '"', $text);
        if ($css != null && gettype($css) == "array" && $nl == true) {
            $this->error_text .=  '\n' . $text;
            foreach ($css as $key => $value) {
                if ($value == null || $value == "") {
                    continue;
                } else { 
                    array_push($this->error_css, str_replace("'", '"', $value));
                }
            }
        } else if ($css == null && $nl == true) {
            $this->error_text .= '\n' . $text;
        } else if ($css != null && gettype($css) == "array" && $nl == false) {
            $this->error_text .= $text;
            foreach ($css as $key => $value) {
                if($value == null || $value == "") {
                    continue;
                } else { 
                    array_push($this->error_css, str_replace("'", '"', $value));
                }
            }
        } else if ($css == null && $nl == false) {
            $this->error_text .= $text;
        } else if ($css != null && gettype($css) != "array" && $nl == "true") {
            $this->error_text .= '\n' . $text;
            array_push($this->error_css, str_replace("'", '"', $value));
        } else {
            $this->error_text .= "";
        }
        #endregion
    }
    
    public function generate(bool $encode = true, bool $clear = true) {
        #region
        global $LOG;
        $et = str_replace('\n',PHP_EOL.TAB1,str_replace("%c",'',substr($this->error_text,2)));
        file_put_contents("debug.log", "");
        error_log($et,3,"debug.log");
        if ($this->error_css != "" && $this->error_text != "") {
            $return = "console.log('".$this->error_text."',";
            if ($clear) { $return = "<script>console.clear();" . $return; } else { $return = "<script>" . $return; }
            foreach ($this->error_css as $key => $value) {
                $return .= "'" . $value . "',";
            }
            if($encode) {
                return base64_encode(substr($return, 0, -1) . ");var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>");
            } else {
                return substr($return, 0, -1) . ");var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>";
            }
        } else if($this->error_text != "" || $this->error_text != null) {
            $return = "console.log('".$this->error_text."');var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>";
            if ($clear) { $return = "<script>console.clear();" . $return; } else { $return = "<script>" . $return; }
            if ($encode) {
                return base64_encode($return);
            } else {
                return $return;
            }
        } else {
            $return = "console.log('nothing to display');var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>";
            if ($clear) { $return = "<script>console.clear();" . $return; } else { $return = "<script>" . $return; }
            if ($encode) {
                return base64_encode($return);
            } else {
                return $return;
            }
        }
        #endregion
    }

    public function clear() {
        #region
        $this->error_text = "";
        $this->error_css = array();
        return true;
        #endregion
    }
}

?>