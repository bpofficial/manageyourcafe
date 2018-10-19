<?php

function session_check($variable_name, $session) {
    if(array_key_exists($variable_name, $session)) {
        return (empty($session[$variable_name])) ? FALSE : TRUE;
    } else {
        return "invalid";
    }
}
function escape($value) {
    $return = '';
    for($i = 0; $i < strlen($value); ++$i) {
        $char = $value[$i];
        $ord = ord($char);
        if($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
            $return .= $char;
        else
            $return .= '\\x' . dechex($ord);
    }
    return $return;
}

function sanitize($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = escape($data);
    return $data;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function email($email, $data, $type) {
    #region
    global $name, $conn, $error, $DIR;
    if($type == "roster") {
        $data = json_decode($data,true);
        $name = ($DIR != "dev") ? ucfirst($name) : "Developer";
        try {
            $mail = new PHPMailer(true);
            $mail->setFrom('mail@manageyour.cafe', $name);                           
            $mail->isSMTP();                                     
            $mail->Host = 's121.syd2.hostingplatform.net.au';
            $mail->SMTPAuth = true;                       
            $mail->Username = 'mail@manageyour.cafe';      
            $mail->Password = '$$!MailPassword!$';          
            $mail->SMTPSecure = 'ssl';                        
            $mail->Port = 465;    
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $data['title'];
            $mail->Body = base64_decode($data['content']);
            $mail->send();
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%cMessage could not be sent. %cMailer Error: %c' . $e->getMessage() . '%c', ['font-weight:bold;', 'color:black;', 'color:red;', 'font-style:italic;','color:black'], true);
            return false;
        }
        return true;
    } else {
        return false;
    }
    #endregion
}
                                    
?>