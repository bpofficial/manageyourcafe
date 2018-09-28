<?php
// Includes and Heads {
global $LOG;
$LOG = "error_log.log";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../php/vendor/autoload.php';
error_reporting(E_ALL); // Error engine - always ON!
ini_set('display_errors', FALSE); // Error display - OFF in production env or real server
ini_set('log_errors', TRUE); // Error logging
ini_set('error_log', $LOG); // Logging file
//}
// Everything {
// Includes and Heads {
session_start();
include_once('../php/config.php');
include_once('../php/classes.php');
$error = new errorHandle;
$name = $_SESSION['uname'];
$uppername = ucfirst($name);
$store_id = $_SESSION['store_id'];
$_SESSION['debug'] = true;
//}
// Functions {
function humanTiming ($time)
{

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

}

function email($type, $data, $date) {
    global $uppername, $name, $conn, $er;
    if($type == "roster") {
        $data = json_decode($data,true);
        try {
            $stv = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$store_id'");
            $stv->execute();
            $staff = $stv->fetchAll(PDO::FETCH_ASSOC);
        } catch ( PDOException $e ) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                $error->add_error("%cDetails: %c" . print_r($e), ['font-weight:bold', 'color:black;'], true);
                exit(
                    json_encode(
                        array(
                            'success' =>  false,
                            'value' => 'Errors.',
                            'errors' =>  $$error->generate()
                        )
                    )
                );
            } else {
                exit(
                    json_encode(
                        array(
                            'success' =>  false,
                            'value' =>  '500 Server-side error.'
                        )
                    )
                );
            }
        }
        try {
            $mail = new PHPMailer(true);
            $mail->setFrom('mail@manageyour.cafe', $uppername);
            $mail->SMTPDebug = 1;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 's121.syd2.hostingplatform.net.au';     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'mail@manageyour.cafe';             // SMTP username
            $mail->Password = '$$!MailPassword!$';                // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            foreach($staff as $key => $val) {
                $settings = json_decode($val['settings'],true);
                if($settings['getEmails']) {
                    $mail->addAddress($val['email']);
                } else {
                    continue;
                }
            }
            $mail->isHTML(true);
            $mail->Subject = $data['title'];
            if($data['comments'] === null) {
                $mail->Body = base64_decode($data['content']);
            } else {
                $mail->Body = $data['comments'] . '<br/>' . base64_decode($data['content']);
            }
            $mail->send();
        } catch (Exception $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%cMessage could not be sent. %cMailer Error: %c' . $mail->ErrorInfo . '%c', ['font-weight:bold;', 'color:black;', 'color:red;', 'font-style:italic;','color:black'], true);
                exit(
                    json_encode(
                        array(
                            'success' =>  false,
                            'value' => 'Errors.',
                            'errors' =>  $error->generate()
                        )
                    )
                );
            } else {
                error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo, 3, "logs/mail.log");
                exit(
                    json_encode(
                        array(
                            'success' =>  false,
                            'value' =>  'Errors.'
                        )
                    )
                );
            }
        }
    } else {
        return;
    }
}
//}
// Admin Notices {*/        
error_log("Starting at top",3,"loglog.log");                                                                                      
if($_REQUEST['message'] == "REQ_PAGE" && $_SESSION['priv']['level'] == 3) {
    error_log("Starting at admin notices",3,"loglog.log");
    $html = <<<EOT
    <div id="notices" class="section__content section__content--p30">
        <div class="container-fluid" id="content">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">Notices</h2>
                    <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#newPostModal">
                        <i class="zmdi zmdi-plus"></i>new post</button>
                </div><br />
EOT;
            try {
                $st = $conn->prepare("SELECT * FROM `notices` WHERE `store_id`='$store_id'");
                $st->execute();
                $count = $st->rowCount();
                $data = $st->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                }
            }
            $inner = "";
            $body = "";
            foreach ($data as $key => $value) {
                $id = $value['id'];
                try{
                    $poster = $value['posted_by'];
                    error_log($poster,3,$LOG);
                    $type = $value['type'];
                    if($type == "notice") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        $content = "<p class=\"card-text\">" . $content['content'] . "</p>";
                    } else if ($type == "roster") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        if(base64_decode($content['content'] == false)) {
                            $error->add_error("Counld't decode content from base64.", ['font-weight:bold;', 'color:red;'], true);
                        } else {
                            $content = base64_decode($content['content']);
                        }
                    } else {
                        $error->add_error("%cError: %cUnknown type around L183.", ['font-weight:bold;', 'color:red;'], true);
                    }
                    if($title === null || $content === null) {throw new Exception("No content");}
                    $since = humanTiming($value['date']);
                } 
                catch(Exception $e){ 
                    if($_SESSION['debug']) {
                        $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    } else {
                        error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    }
                    continue;
                }
                try {
                    $sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster' AND `store_id`='$store_id'");
                    $sto->execute();
                    $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
                } catch (Exception $e) {
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        if($_SESSION['debug']) {
                            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                        } else {
                            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                        }
                    } else {
                        throw $e;
                    }
                    continue;
                }
                if ($poster_priv['level'] == 3) {
                    $badge = "badge badge-danger";
                } else if ($poster_priv['level'] == 2) {
                    $badge = "badge badge-success";
                } else {
                    $badge = "badge badge-primary";
                }
                
                $com = $conn->prepare("SELECT * FROM `comments` WHERE `notice_id`='$id' AND `store_id`='$store_id'");
                $com->execute();
                $comment_count = $com->rowCount();
                if($comment_count > 0) {
                    $comments = $con->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $comment_count = 0;
                }
                $poster = ucfirst($poster);
                $inner = <<<EOT
                <div id="$id" class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" id="card-$id">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <strong class="card-title">$title</strong>
                                    </div> 
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <span style="cursor:pointer;" class="float-right">
                                            <a id="remove-notice" name="$id">
                                                &nbsp&nbsp<i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                        <strong>
                                            <span class="$badge float-right mt-1\">$poster</span>
                                        </strong>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-8 col-sm-6 col-xs-3">
                                    <span>
                                        <small>$since</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="post-card-$id" class="col-lg-12 col-md-8 col-sm-6 col-xs-3"> 
                                    $content 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOT;
            $body = $inner . $body;
        }
            $html .= $body . <<<EOT
            </div>
        </div>
    </div>
    <div class="modal fade show" id="newPostModal" tabindex="-100" role="dialog" aria-labelledby="largeModalLabel" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">New post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="newpost">
                        <div class="form-group">
                            <textarea class="form-control" name="title" id="post-title" placeholder="Title" rows="1"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="content" id="post-content" placeholder="Message" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="submit" class="btn btn-primary" type="submit" data-dismiss="modal" form="newpost" >Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(function() {
        $("button#submit").click(function(e) {
            e.preventDefault();
            var formdata = $("#newpost").serializeArray();
            var data = {};
            $(formdata).each(function(index, obj){
                data[obj.name] = obj.value;
            });
            $.ajax({
                type: 'POST',
                url: 'backend/ajax/noticesfunc.php',
                dataType: 'json',
                data:
                    {
                        message: "NOTICE_POST",
                        data: window.btoa(JSON.stringify(data))
                    },
                success: function(e){
                    var msg = {proto: "UP_NOTI"}, mes = {proto: "SN_NOTI", title: '$uppername made a new post', body: data['title']};
                    window.client.send(JSON.stringify(msg));
                    window.client.send(JSON.stringify(mes));
                },
                error: function(result){}
            });
            $('#newPostModal').modal('hide');
        });
        $('body').on('click', '#remove-notice', function (e) {
            var id = this.name;
            $('div#' + id).remove();
            $.ajax({
                type: 'POST',
                url: 'backend/ajax/noticesfunc.php',
                dataType: 'json',
                data: 
                    {
                        message: "NTC_RM",
                        value: id
                    },
                success: function(result) {},
                error: function(results) {
                    disError('Failed to remove notice.',true);
                }
            });
        });
    });
    </script>
</div>
EOT;
    header('Content-type: text/html');
    if($_SESSION['debug']) {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html),
                    'errors' => $error->generate()
                )
            )
        );
    } else {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html)
                )
            )
        );
    }
//}
// Supervisor Notices {*/
} else if ($_REQUEST['message'] == "REQ_PAGE" && $_SESSION['priv']['level'] == 2) {
    $html = <<<EOT
    <div id="notices" class="section__content section__content--p30">
        <div class="container-fluid" id="content">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">Notices</h2>
                    <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#newPostModal">
                        <i class="zmdi zmdi-plus"></i>new post</button>
                </div><br />
EOT;
            try {
                $st = $conn->prepare("SELECT * FROM `notices` WHERE `store_id`='$store_id'");
                $st->execute();
                $count = $st->rowCount();
                $data = $st->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    exit(
                        json_encode(
                            array(
                                'success' => false,
                                'value' => '500 Server-side error.',
                                'errors' => $error->generate()
                            )
                        )
                    );
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    exit(
                        json_encode(
                            array(
                                'success' => false,
                                'value' => '500 Server-side error.'
                            )
                        )
                    );
                }
            }
            $inner = "";
            foreach ($data as $key => $value) {
                $id = $value['id'];
                try {
                    $poster = $value['posted_by'];
                    error_log($poster,3,$LOG);
                    $type = $value['type'];
                    if($type == "notice") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        $content = "<p class=\"card-text\">" . $content['content'] . "</p>";
                    } else if ($type == "roster") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        if(!(base64_decode($content['content']))) {
                            throw new Exception("Counld't decode content from base64.");
                        } else {
                            $content = base64_decode($content['content']);
                        }
                    } else {
                        throw new Exception("Unknown type");
                    }
                    if($title === null || $content === null) {throw new Exception("No content");}
                    $since = humanTiming($value['date']);
                } 
                catch(Exception $e){ 
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        if($_SESSION['debug']) {
                            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                        } else {
                            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                        }
                    } else {
                        throw $e;
                    }
                    continue;
                }
                try {
                    $sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster' AND `store_id`='$store_id'");
                    //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                    $sto->execute();
                    //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                    $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
                } catch (Exception $e) {
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        if($_SESSION['debug']) {
                            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                        } else {
                            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                        }
                    } else {
                        throw $e;
                    }
                    continue;
                }
                if ($poster_priv['level'] == 3) {
                    $badge = "badge badge-danger";
                } else if ($poster_priv['level'] == 2) {
                    $badge = "badge badge-success";
                } else {
                    $badge = "badge badge-primary";
                }
                $inner = <<<EOT
                <div id="$id" class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" id="card-$id">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <strong class="card-title">$title</strong>
                                    </div> 
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <span style="cursor:pointer;" class="float-right">
                                            <a id="remove-notice" name="$id">
                                                &nbsp&nbsp<i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                        <strong>
                                            <span class="$badge float-right mt-1\">$poster</span>
                                        </strong>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-8 col-sm-6 col-xs-3">
                                    <span>
                                        <small>$since</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="post-card-$id" class="col-lg-12 col-md-8 col-sm-6 col-xs-3"> 
                                    $content 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOT;
            $body = $inner . $body;
        }
            $html .= <<<EOT
            </div>
        </div>
    </div>
    <div class="modal fade show" id="newPostModal" tabindex="-100" role="dialog" aria-labelledby="largeModalLabel" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">New post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="newpost">
                        <div class="form-group">
                            <textarea class="form-control" name="title" id="post-title" placeholder="Title" rows="1"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="content" id="post-content" placeholder="Message" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="submit" class="btn btn-primary" type="submit" data-dismiss="modal" form="newpost" >Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(function() {
        $("button#submit").click(function(e) {
            e.preventDefault();
            var formdata = $("#newpost").serializeArray();
            var data = {};
            $(formdata).each(function(index, obj){
                data[obj.name] = obj.value;
            });
            $.ajax({
                type: 'POST',
                url: 'backend/ajax/noticesfunc.php',
                dataType: 'json',
                data:
                    {
                        message: "NOTICE_POST",
                        data: window.btoa(JSON.stringify(data))
                    },
                success: function(result){},
                error: function(result){}
            });
            $('#newPostModal').modal('hide');
        });
    });
    </script>
</div>
EOT;
    header('Content-type: text/html');
    if($_SESSION['debug']) {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html),
                    'errors' => $error->generate()
                )
            )
        );
    } else {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html)
                )
            )
        );
    }  
//}
// Staff Notices {*/
} else if ($_REQUEST['message'] == "REQ_PAGE" && $_SESSION['priv']['level'] == 1) {
    $html = <<<EOT
    <div id="notices" class="section__content section__content--p30">
        <div class="container-fluid" id="content">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">Notices</h2>
                </div><br />
EOT;
            try {
                $st = $conn->prepare("SELECT * FROM `notices` WHERE `store_id`='$store_id'");
                $st->execute();
                $count = $st->rowCount();
                $data = $st->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    exit(
                        json_encode(
                            array(
                                'success' => false,
                                'value' => '500 Server-side error.',
                                'errors' => $error->generate()
                            )
                        )
                    );
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    exit(
                        json_encode(
                            array(
                                'success' => false,
                                'value' => '500 Server-side error.'
                            )
                        )
                    );
                }
            }
            $inner = ""; $body = "";
            foreach ($data as $key => $value) {
                $id = $value['id'];
                try{
                    $poster = $value['posted_by'];
                    error_log($poster,3,$LOG);
                    $type = $value['type'];
                    if($type == "notice") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        $content = "<p class=\"card-text\">" . $content['content'] . "</p>";
                    } else if ($type == "roster") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        if(!(base64_decode($content['content']))) {
                            throw new Exception("Counld't decode content from base64.");
                        } else {
                            $content = base64_decode($content['content']);
                        }
                    } else {
                        throw new Exception("Unknown type");
                    }
                    if($title === null || $content === null) {throw new Exception("No content");}
                    $since = humanTiming($value['date']);
                } 
                catch(Exception $e){ 
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        if($_SESSION['debug']) {
                            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                        } else {
                            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                        }
                    } else {
                        throw $e;
                    }
                    //continue;
                }
                try {
                    $sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster' AND `store_id`='$store_id'");
                    //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                    $sto->execute();
                    //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                    $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
                } catch (Exception $e) {
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        if($_SESSION['debug']) {
                            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                        } else {
                            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                        }
                    } else {
                        throw $e;
                    }
                    //continue;
                }
                if ($poster_priv['level'] == 3) {
                    $badge = "badge badge-danger";
                } else if ($poster_priv['level'] == 2) {
                    $badge = "badge badge-success";
                } else {
                    $badge = "badge badge-primary";
                }
                
                $com = $conn->prepare("SELECT * FROM `comments` WHERE `notice_id`='$id' AND `store_id`='$store_id'");
                $com->execute();
                $comment_count = $com->rowCount();
                if($comment_count > 0) {
                    $comments = $con->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $comment_count = 0;
                }
                $inner = <<<EOT
                <div id="$id" class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" id="card-$id">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <strong class="card-title">$title</strong>
                                    </div> 
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <span style="cursor:pointer;" class="float-right">
                                            <a id="remove-notice" name="$id">
                                                &nbsp&nbsp<i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                        <strong>
                                            <span class="$badge float-right mt-1\">$poster</span>
                                        </strong>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-8 col-sm-6 col-xs-3">
                                    <span>
                                        <small>$since</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="post-card-$id" class="col-lg-12 col-md-8 col-sm-6 col-xs-3"> 
                                    $content 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOT;
            $body = $inner . $body;
        }
        $html .= <<<EOT
            </div>
        </div>
    </div>
EOT;
    header('Content-type: text/html');
    if($_SESSION['debug']) {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html),
                    'errors' => $error->generate()
                )
            )
        );
    } else {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html)
                )
            )
        );
    }  
//}
// Update Notices{*/
} else if ($_REQUEST['message'] == "NOTICE_UPDATE" && $_SESSION['priv']['post'] == "true") {
    $html = <<<EOT
        <div class="container-fluid" id="content">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">Notices</h2>
                    <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#newPostModal">
                        <i class="zmdi zmdi-plus"></i>new post</button>
                </div><br />
EOT;
            try {
                $st = $conn->prepare("SELECT * FROM `notices` WHERE `store_id`='$store_id'");
                $st->execute();
                $count = $st->rowCount();
                $data = $st->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    exit(
                        json_encode(
                            array(
                                'success' => false,
                                'value' => '500 Server-side error.',
                                'errors' => $error->generate()
                            )
                        )
                    );
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    exit(
                        json_encode(
                            array(
                                'success' => false,
                                'value' => '500 Server-side error.'
                            )
                        )
                    );
                }
            }
            $inner = ""; $body = "";
            foreach ($data as $key => $value) {
                $id = $value['id'];
                try{
                    $poster = $value['posted_by'];
                    error_log($poster,3,$LOG);
                    $type = $value['type'];
                    if($type == "notice") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        $content = "<p class=\"card-text\">" . $content['content'] . "</p>";
                    } else if ($type == "roster") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        if(!(base64_decode($content['content']))) {
                            throw new Exception("Counld't decode content from base64.");
                        } else {
                            $content = base64_decode($content['content']);
                        }
                    } else {
                        throw new Exception("Unknown type");
                    }
                    if($title === null || $content === null) {throw new Exception("No content");}
                    $since = humanTiming($value['date']);
                } 
                catch(Exception $e){ 
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    } else {
                        throw $e;
                    }
                    continue;
                }
                try {
                    $sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster' AND `store_id`='$store_id'");
                    //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                    $sto->execute();
                    //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                    $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
                } catch (Exception $e) {
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        error_log("Exception on line " + $e->getLine() + ": " + $e->getMessage() + PHP_EOL,3,$LOG);
                    } else {
                        throw $e;
                    }
                    continue;
                }
                if ($poster_priv['level'] == 3) {
                    $badge = "badge badge-danger";
                } else if ($poster_priv['level'] == 2) {
                    $badge = "badge badge-success";
                } else {
                    $badge = "badge badge-primary";
                }
                $inner = <<<EOT
                <div id="$id" class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" id="card-$id">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <strong class="card-title">$title</strong>
                                    </div> 
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <span style="cursor:pointer;" class="float-right">
                                            <a id="remove-notice" name="$id">
                                                &nbsp&nbsp<i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                        <strong>
                                            <span class="$badge float-right mt-1\">$poster</span>
                                        </strong>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-8 col-sm-6 col-xs-3">
                                    <span>
                                        <small>$since</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="post-card-$id" class="col-lg-12 col-md-8 col-sm-6 col-xs-3"> 
                                    $content 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOT;
            $body = $inner . $body;
        }
    $html .= $body . '</div></div>';
    header('Content-type: text/html');
    if($_SESSION['debug']) {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html),
                    'errors' => $error->generate()
                )
            )
        );
    } else {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html)
                )
            )
        );
    }
} else if ($_REQUEST['message'] == "NOTICE_UPDATE" && $_SESSION['priv']['post'] == "false") {
        $html = <<<EOT
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Notices</h2>
                    </div><br />
EOT;
                try {
                $st = $conn->prepare("SELECT * FROM `notices` WHERE `store_id`='$store_id'");
                $st->execute();
                $count = $st->rowCount();
                $data = $st->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    exit(
                        json_encode(
                            array(
                                'success' => false,
                                'value' => '500 Server-side error.',
                                'errors' => $error->generate()
                            )
                        )
                    );
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    exit(
                        json_encode(
                            array(
                                'success' => false,
                                'value' => '500 Server-side error.'
                            )
                        )
                    );
                }
            }
            $inner = ""; $body = "";
            foreach ($data as $key => $value) {
                $id = $value['id'];
                try{
                    $poster = $value['posted_by'];
                    error_log($poster,3,$LOG);
                    $type = $value['type'];
                    if($type == "notice") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        $content = "<p class=\"card-text\">" . $content['content'] . "</p>";
                    } else if ($type == "roster") {
                        $content = json_decode(stripslashes($value['content']),true);
                        $title = $content['title'];
                        if(!(base64_decode($content['content']))) {
                            throw new Exception("Counld't decode content from base64.");
                        } else {
                            $content = base64_decode($content['content']);
                        }
                    } else {
                        throw new Exception("Unknown type");
                    }
                    if($title === null || $content === null) {throw new Exception("No content");}
                    $since = humanTiming($value['date']);
                } 
                catch(Exception $e){ 
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        if($_SESSION['debug']) {
                            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                        } else {
                            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                        }
                    } else {
                        throw $e;
                    }
                    continue;
                }
                try {
                    $sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster' AND `store_id`='$store_id'");
                    //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                    $sto->execute();
                    //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                    $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
                } catch (Exception $e) {
                    if ($e instanceof PDOException || $e instanceof Exception) {
                        if($_SESSION['debug']) {
                            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                        } else {
                            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                        }
                    } else {
                        throw $e;
                    }
                    continue;
                }
                if ($poster_priv['level'] == 3) {
                    $badge = "badge badge-danger";
                } else if ($poster_priv['level'] == 2) {
                    $badge = "badge badge-success";
                } else {
                    $badge = "badge badge-primary";
                }
                $inner = <<<EOT
                <div id="$id" class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" id="card-$id">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <strong class="card-title">$title</strong>
                                    </div> 
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <span style="cursor:pointer;" class="float-right">
                                            <a id="remove-notice" name="$id">
                                                &nbsp&nbsp<i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                        <strong>
                                            <span class="$badge float-right mt-1\">$poster</span>
                                        </strong>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-8 col-sm-6 col-xs-3">
                                    <span>
                                        <small>$since</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="post-card-$id" class="col-lg-12 col-md-8 col-sm-6 col-xs-3"> 
                                    $content 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOT;
            $body = $inner . $body;
        }
    if($_SESSION['debug']) {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html),
                    'errors' => $error->generate()
                )
            )
        );
    } else {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html)
                )
            )
        );
    }
//}
// New Post{*/
} else if ($_POST['message'] == "NOTICE_POST") {
    $notice = base64_decode($_POST['data']);
    $datetime = date("Y-m-d H:i:s");
    $user_can_post = $_SESSION['priv']['post'];
    $type = "notice";
    if($user_can_post) {
        try {
            $st = $conn->prepare("INSERT INTO `notices` (store_id, date, posted_by, content, type) VALUES ('$store_id','$datetime', '$name', '$notice', '$type')");
            $st->execute();
            echo json_encode(
                array(
                    'success' => true, 
                    'value' => 'Posted'
                )
            );
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                echo json_encode(
                        array(
                            'success' => false,
                            'value' => '500 Server-side error.',
                            'errors' => $error->generate()
                        )
                    );
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    echo json_encode(
                        array(
                            'success' => false,
                            'value' => '500 Server-side error.'
                        )
                    );
            }
        }
    } else {
        header('Content-type: text/html');
        echo json_encode(
            array(
                'success' => false,
                'value' => "RGT_CNT_POST",
                'errors' => $error->generate()
            )
        );
    }
} else if ($_POST['message'] == "ROSTER_POST") {
    $r = new roster;
    $html = $r->generateUser($store_id, "notices");
    /*
    $roster = json_decode(stripslashes($_POST['data']),true);
    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    $comment = $roster['comments'];
    $html = <<<EOT
    <p>
        $comment
    </p>
    <div class="table-responsive table--no-card m-b-30">
        <table class='table table-bordered table-earning'>
            <thead align='center'>
                <tr>
                    <td>Name</td>
                    <td>Monday</td>
                    <td>Tuesday</td>
                    <td>Wednesday</td>
                    <td>Thursday</td>
                    <td>Friday</td>
                    <td>Saturday</td>
                    <td>Sunday</td>
                </tr>
            </thead>
            <tbody align='left' style="text-align:left">
EOT;
    try {
        $st = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$store_id'");
        $st->execute();
        $staff = $st->fetchAll(PDO::FETCH_ASSOC);
        $staffCount = $st->rowCount();
    } catch (PDOException $e) {
        if($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
        } else {
            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
        }
    }
    $body = "";
    
    for ($count = 0; $count < $staffCount; $count++) {
        $nname = ucfirst($staff[$count]['uname']);
        $body .= "<tr><td>" . $nname . "</td>";
        for ($x = 0; $x < 7; $x++) {
            $name_start = $roster[$days[$x]][$nname]["start"];
            $name_finish = $roster[$days[$x]][$nname]["finish"];
            error_log("Start: ".$name_start.PHP_EOL,3,"log.log");
            error_log("Finish: ".$name_finish.PHP_EOL,3,"log.log");
            if($name_start == "startTime" || $name_finish == "finishTime") {
                $name_start = "Not working";
                $name_finish = "";
            }
            $body .= <<<EOT
                <td>
                    <div>$name_start</div>
                    <div>$name_finish</div>
                </td>
EOT;
        }
        $body .= "</tr>";
    }
    $html .= $body . "</tr></tbody></table></div>";
    */
    $css = <<<EOT
        <style> .m-b-30{margin-bottom:30px}.table{margin:0}.table-responsive{padding-right:1px}.table-responsive .table--no-card{-webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px; -webkit-box-shadow:0 2px 5px 0 rgba(0,0,0,.1); -moz-box-shadow:0 2px 5px 0 rgba(0,0,0,.1); box-shadow:0 2px 5px 0 rgba(0,0,0,.1)}.table-earning thead th{background:#333; font-size:16px; color:#fff; vertical-align:middle; font-weight:400; text-transform:capitalize; line-height:1; padding:22px 40px; white-space:nowrap}.table-earning thead th.text-right{padding-left:15px; padding-right:65px}.table-earning tbody td{color:gray; padding:12px 40px; white-space:nowrap}.table-earning tbody td.text-right{padding-left:15px; padding-right:65px}.table-earning tbody tr:hover td{color:#555; cursor:pointer}.table-bordered{border:1px solid #dee2e6}.table-bordered td,.table-bordered th{border:1px solid #dee2e6}.table-bordered thead td,.table-bordered thead th{border-bottom-width:2px}@media(max-width:575.98px){.table-responsive-sm{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-sm>.table-bordered{border:0}}@media(max-width:767.98px){.table-responsive-md{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-md>.table-bordered{border:0}}@media(max-width:991.98px){.table-responsive-lg{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-lg>.table-bordered{border:0}}@media(max-width:1199.98px){.table-responsive-xl{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-xl>.table-bordered{border:0}}.table-responsive{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive>.table-bordered{border:0} </style>  
EOT;
    $notice = json_encode(
        array(
            'title' => 'Roster for week starting ' . $roster['startDate'], 
            'content' => addslashes(base64_encode($html))
        )
    );
    
    $mail = json_encode(
        array(
            'title' => 'Roster for week starting ' . $roster['startDate'], 
            'content' => base64_encode($css . $html), 
            'comments' => $comment
        )
    );
    
    $datetime = date("Y-m-d H:i:s");
    $user_can_post = $_SESSION['priv']['post'];
    $type = "roster";
    if($user_can_post) {
        $st = $conn->prepare("INSERT INTO `notices` (store_id, date, posted_by, content, type) VALUES ('$store_id', '$datetime', '$name', '$notice', '$type')");
        $st->execute();
        //email('roster', $mail, $roster['startDate']);
        echo json_encode(
            array(
                'success' => true,
                'value' => "Posted"
            )
        );
    } else {
        header('Content-type: text/html');
        echo json_encode(
            array(
                'success' => false,
                'value' => "RGT_CNT_POST"
            )
        );
    }
} else if ($_POST['message'] == "NTC_RM") {
    $id = $_POST['value'];
    header('Content-type: text/html');
    try {
        $st = $conn->prepare("DELETE FROM `notices` WHERE `id`='$id' AND `store_id`='$store_id'");
        $st->execute();
        echo json_encode(
            array(
                'success' => true, 
                'value' => '1'
            )
        );
    } catch (PDOException $e) {
        if($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo json_encode(
                    array(
                        'success' => false,
                        'value' => '500 Server-side error.',
                        'errors' => $error->generate()
                    )
                );
        } else {
            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
            echo json_encode(
                    array(
                        'success' => false,
                        'value' => '500 Server-side error.'
                    )
                );
        }
    }
} else {
    header('Content-type: text/html');
    exit(
        json_encode(
            array(
                'success' => false,
                'value' => "Unknown",
                'errors' => 'none'
            )
        )
    );
}
?>