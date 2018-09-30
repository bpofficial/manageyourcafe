<?php
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

function exitf(string $value, bool $errors = false, string $type = "GET", $e = null, bool $stress = false, $time = null) {
    global $error;
    if ($type === "GET") {
        #region
        if ($errors) {
            if ($_SESSION['debug']) {
                try {
                    if($e != null) {
                        $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    }
                    if (!$stress) {
                        exit(json_encode(array('success' => false,'value' => $value,'errors' => $error->generate())));
                    } else {
                        exit(json_encode(array('success' => false,'value' => $value,'errors' => $error->generate(), 'time' => $time)));
                    }
                } catch (Exception $ex) {
                    exit(json_encode(array('success' => false,'value' => '...')));
                }
            } else {
                try {
                    if($e != null) {
                        error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    }
                    if (!$stress) {
                        exit(json_encode(array('success' => false,'value' => $value)));
                    } else {
                        exit(json_encode(array('success' => false,'value' => $value, 'time' => $time)));
                    }
                } catch (Exception $ex) {
                    exit(json_encode(array('success' => false,'value' => '...')));
                }
            }
        } else {
            if ($_SESSION['debug']) {
                try {
                    if (!$stress) {
                        exit(json_encode(array('success' => true,'value' => $value,'errors' => $error->generate())));
                    } else {
                        exit(json_encode(array('success' => true,'value' => $value,'errors' => $error->generate(), 'time' => $time)));
                    }
                } catch (Exception $ex) {
                    exit(json_encode(array('success' => false,'value' => '...')));
                }
            } else {
                try {
                    if (!$stress) {
                        exit(json_encode(array('success' => true,'value' => $value)));
                    } else {
                        exit(json_encode(array('success' => true,'value' => $value, 'time' => $time)));
                    }
                } catch (Exception $ex) {
                    exit(json_encode(array('success' => false,'value' => '...')));
                }
            }
        }
        #endregion
    } else if ($type === "POST") {
        #region
        if ($errors) {
            if ($_SESSION['debug']) {
                try {
                    if($e != null) {
                        $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    }
                    if (!$stress) {
                        return json_encode(array('success' => false,'value' => $value,'errors' => $error->generate()));
                    } else {
                        return json_encode(array('success' => false,'value' => $value,'errors' => $error->generate(), 'time' => $time));
                    }
                } catch (Exception $ex) {
                    return json_encode(array('success' => false,'value' => '...'));
                }
            } else {
                try {
                    if($e != null) {
                        error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    }
                    if (!$stress) {
                        return json_encode(array('success' => false,'value' => $value));
                    } else {
                        return json_encode(array('success' => false,'value' => $value, 'time' => $time));
                    }
                } catch (Exception $ex) {
                    return json_encode(array('success' => false,'value' => '...'));
                }
            }
        } else {
            if ($_SESSION['debug']) {
                try {
                    if (!$stress) {
                        return json_encode(array('success' => true,'value' => $value,'errors' => $error->generate()));
                    } else {
                        return json_encode(array('success' => true,'value' => $value,'errors' => $error->generate(), 'time' => $time));
                    }
                } catch (Exception $ex) {
                    return json_encode(array('success' => true,'value' => '...'));
                }
            } else {
                try {
                    if (!$stress) {
                        return json_encode(array('success' => true,'value' => $value));
                    } else {
                        return json_encode(array('success' => true,'value' => $value, 'time' => $time));
                    }
                } catch (Exception $ex) {
                    return json_encode(array('success' => false,'value' => '...'));
                }
            }
        }
        #endregion
    }
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function email($email, $data, $type) {
    #region
    global $uppername, $name, $conn, $error, $store_id;
    if($type == "roster") {
        $data = json_decode($data,true);
        try {
            $mail = new PHPMailer(true);
            $mail->setFrom('mail@manageyour.cafe', $uppername);                           
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
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%cMessage could not be sent. %cMailer Error: %c' . $e->getMessage() . '%c', ['font-weight:bold;', 'color:black;', 'color:red;', 'font-style:italic;','color:black'], true);
            } else {
                error_log('Message could not be sent. Mailer Error: ' . $e->getMessage(), 3, $LOG);
            }
            return false;
        } catch (phpmailerException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%cMessage could not be sent. %cMailer Error: %c' . $e->errorMessage() . '%c', ['font-weight:bold;', 'color:black;', 'color:red;', 'font-style:italic;','color:black'], true);
            } else {
                error_log('Message could not be sent. Mailer Error: ' . $e->errorMessage(), 3, $LOG);
            }
            return false;
        }
        return true;
    } else {
        return;
    }
    #endregion
}
                                    
?>