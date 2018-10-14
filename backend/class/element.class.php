<?php

class element {

    public function __construct(string $store_id, string $data = "", $options = null) {

    }

    private function changeInput(string $data, $store_id, $options) {
        if ( empty($options) || $options != null ) {
            $st = $conn->prepare("SELECT `customisation` FROM `staff` WHERE `store_id`='$store_id' AND `uname`='$name'");
            $st->execute();
            $options = $st->fetchColumn();
            $options = json_decode($options,true);
            customisationReplacement($data, $store_id, $options);
        } else {
            $tag_string = "";
            $data = str_replace("\">","\" >", $data);
            foreach($options as $element => $attributes) {
                $e = "<".$element." ";
                $f = "<".$element.">";
                if(strpos($data, $e) || strpos($data,$f)) {
                    $tag_regex_array = array();
                    $regex = '<'.$element.' (.*) >';
                    preg_match($regex, $data, $tag_regex_array);
                    $tag_string = " ".$tag_regex_array[1]." "; //the string that holds all the attributes in the tag selected
                    $attribute_name_string = preg_replace('/="(.*?)"/',null,$tag_string);
                    preg_match_all('/\s(.*?)=/',$tag_string,$tag_regex_array);
                    $attribute_name_string = trim($attribute_name_string," ");
                    $attribute_name_array = preg_split('/\s+/i',$attribute_name_string); //the array that holds all the names of the attributes in the tag selected
                    $s_id = array_search("id",$attribute_name_array);
                    if($s_id == 00 || $s_id > 0 && $s_id != null) {
                        preg_match('/id="(.*?)"/', $tag_string, $id);
                        $id = $id[1];
                        $line_selection_regex = '/<'.$element.'\s*?id="'.$id.'".*?(.*?)>/';
                        preg_match($line_selection_regex, $data, $line_of_focus);
                        $line_of_focus = $line_of_focus[0]; //used to edit and modify to then replace in the original string
                        $LINE_TO_REPLACE = $line_of_focus; //used for regex to find and replace with the newly created line
                    } else if (array_search("name",$attribute_name_array)) {
                        preg_match('/name="(.*?)"/', $tag_string, $name);
                        $name = $name[1];
                        $line_selection_regex = '<'.$element.'\s*?name="'.$name.'".*?(.*?)>';
                        preg_match($line_selection_regex, $data, $line_of_focus);
                        $line_of_focus = $line_of_focus[0]; //used to edit and modify to then replace in the original string
                        $LINE_TO_REPLACE = $line_of_focus; //used for regex to find and replace with the newly created line
                    }
                    foreach($attribute_name_array as $key => $attribute_name) {
                        if(array_key_exists($attribute_name, $attributes) && !is_array($attributes[$attribute_name])) {
                            preg_match('/'.$attribute_name.'="(.*?)" /i', $line_of_focus, $reg);
                            if ((strpos($reg[1], " ".$attributes[$attribute_name]." ") == false) && 
                                (strpos($reg[1], $attributes[$attribute_name]." ") == false) && 
                                (strpos($reg[1], " ".$attributes[$attribute_name]) == false)) {
                                $change = $reg[1] . " " . $attributes[$attribute_name];
                            } else {
                                $change = $reg[1];
                            }
                            $inline_attribute_data = $attribute_name . "=\"" . $reg[1] . "\"";
                            $updated_attribute_data = $attribute_name . "=\"" . $change . "\"";
                            $line_of_focus = str_replace($inline_attribute_data, $updated_attribute_data, $line_of_focus);
                        } else if (is_array($attributes[$attribute_name])) {
                            $change = "";
                            foreach($attributes[$attribute_name] as $option => $setting) {
                                $update = $option . ":" . $setting . ";";
                                preg_match('/'.$attribute_name.'="(.*?)" /i', $line_of_focus, $reg);
                                if ((strpos($reg[1], " ".$update." ") == false) && 
                                    (strpos($reg[1], $update." ") == false) && 
                                    (strpos($reg[1], " ".$update) == false)) {
                                    $change .= $reg[1] . " " . $update;
                                } 
                            }
                            $inline_attribute_data = $attribute_name . "=\"" . $reg[1] . "\"";
                            $updated_attribute_data = $attribute_name . "=\"" . $change . "\"";
                            $line_of_focus = str_replace($inline_attribute_data, $updated_attribute_data, $line_of_focus);
                        }
                    }
                    foreach($attributes as $a_name => $a_value) {
                        if(!is_array($a_value)) {
                            if(strpos($attribute_name_string,$a_name) == false) {
                                $line_of_focus = trim($line_of_focus,">");
                                $line_of_focus .= $a_name . "=\"" . $a_value . "\" >";
                            }
                        } else {
                            if(strpos($attribute_name_string,$a_name) == false) {
                                $update = "";
                                foreach($a_value as $opt => $set) {
                                    $update .= $opt . ":" . $set . ";";
                                }
                                $line_of_focus = trim($line_of_focus,">");
                                $line_of_focus .= $a_name . "=\"" . $update . "\" >";
                            }
                        }
                    }
                    $data = str_replace($LINE_TO_REPLACE, $line_of_focus, $data);
                }
            }
            $data = str_replace("\" >", "\">",$data);
        }
    }

    private function minimiseInput() {

    }

}

?>