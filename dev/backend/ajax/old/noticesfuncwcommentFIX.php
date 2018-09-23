<?php

$LOG = "logs/notices.log";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../../php/vendor/autoload.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
    session_unset();
    session_destroy();
} else {
    
    session_start();
    include('../php/config.php');
    $name = $_SESSION['uname'];
    $uppername = ucfirst($name);
    
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
        global $uppername, $name;
        global $conn;
        if($type == "roster") {
            $data = json_decode($data,true);
            try {
                $stv = $conn->prepare("SELECT * FROM `staff`");
                $stv->execute();
                $staff = $stv->fetchAll(PDO::FETCH_ASSOC);
            } catch ( PDOException $e ) {
                error_log("Error: ". $e.getMessage() ." on line: " .$e.getLine() . PHP_EOL, 3, $LOG);
                error_log("Details: " . print_r($e) . PHP_EOL, 3, $LOG);
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
                error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo, 3, "logs/mail.log");
            }
        } else {
            return;
        }
    }
    /*   _      ____    __  __   ___   _   _       _   _    ___    _____   ___    ____   _____   ____  
        / \    |  _ \  |  \/  | |_ _| | \ | |     | \ | |  / _ \  |_   _| |_ _|  / ___| | ____| / ___| 
       / _ \   | | | | | |\/| |  | |  |  \| |     |  \| | | | | |   | |    | |  | |     |  _|   \___ \ 
      / ___ \  | |_| | | |  | |  | |  | |\  |     | |\  | | |_| |   | |    | |  | |___  | |___   ___) |
     /_/   \_\ |____/  |_|  |_| |___| |_| \_|     |_| \_|  \___/    |_|   |___|  \____| |_____| |____/ 
    */                                                                                                   
    if($_REQUEST['message'] == "REQ_PAGE" && $_SESSION['priv']['level'] == 3) {
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
            		$st = $conn->prepare("SELECT * FROM `notices`");
            		$st->execute();
            		$count = $st->rowCount();
            		$data = $st->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    exit(json_encode(array('success'=>false,'value'=>'unknown')));
                }
        		error_log('----------------  DATA --------------'.PHP_EOL.print_r($data,true).PHP_EOL.PHP_EOL.PHP_EOL,3,"logs/spesh.log");
        		$inner = "";
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
                           error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,"logs/noticesfunc.log");
                        } else {
                            throw $e;
                        }
        			    continue;
        			}
        			try {
            			$sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster'");
                        //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                        $sto->execute();
                        //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                        $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
        			} catch (Exception $e) {
        			    if ($e instanceof PDOException || $e instanceof Exception) {
                           error_log("Exception on line " + $e->getLine() + ": " + $e->getMessage() + PHP_EOL,3,"logs/noticesfunc.log");
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
                    
                    $com = $conn->prepare("SELECT * FROM `comments` WHERE `notice_id`='$id'");
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
                                           <strong class="card-title">
EOT;
                                $inner .= $title . <<<EOT
                                            </strong>
                                        </div> 
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <span style="cursor:pointer;" class="float-right">
                                                <a id="remove-notice" name="$id">
                                                    &nbsp&nbsp<i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                            <strong>
                                                <span class="
EOT;
                                $inner .=  $badge . " float-right mt-1\">" . ucfirst($poster) . <<<EOT
                                                </span>
                                            </strong>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span>
                                            <small>$since</small>
                                            <style>
                                                .quantity {
                                                    border-radius: 100px;
                                                    background: #dc3545;
                                                    color : white;
                                                    padding : 1px 6px; 
                                                }
                                                
                                                span #comments {
                                                    cursor:pointer;
                                                }
                                                
                                                span #comments:hover {
                                                    color: blue;
                                                }
                                                
                                            </style>
                                            <small><span class="float-right" id="comments" name="$id">Comments <span class="quantity"><bold>$comment_count</bold></span></span></small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div id="post-card-$id" style="background-color:red;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
EOT;
                            $inner .= $content . <<<EOT
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" active="false" id="comments-body-$id" style="display:none;background:red;padding-right:0px;padding-left:0px;width:0%;float:right;z-index:2;overflow-wrap:break-word;">
                                        <style>
                                            .divider {
                                                position:relative;
                                                Float:left;
                                                height:100%;
                                                width:1px;
                                                background-color: rgba(0,0,0,.125)
                                            }
                                            .comment-body {
                                                padding: 0.75rem !important;
                                            }
                                        </style>
                                        <div class="divider"></div>
                                        <div style="margin-left:2vw;">
                                            <div style="vertical-align:middle;">
                                                <strong>Comments</strong>
                                                <button class="au-btn au-btn-icon au-btn--blue" style="line-height:25px!important;padding:0 5px!important;float:right;"><i class="zmdi zmdi-plus"></i>comment</button>
                                            </div>
                                            <br />
                                            <div class="card">
                                                <div class="card-body comment-body" style="position:relative;">
                                                    <strong> User </strong><br/>
                                                    <p>
                                                        shfashfihgliahgalghaghljkghlakghlakhglkaghjlkasdhgkaghkasgagg
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
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
                    //grab comments around here 
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
            $('body').on('click', '#comments', function(e) {
            	var name = $(this).attr('name');
                var place = $("#comments-body-"+name).prop('active');
                var col_12 = $("#card-size-12").width();
                var col_8 = $("#card-size-8").width();
                var col_4 = $("#card-size-4").width();
            	if(!(place == "true")) {
            		$("#post-card-"+name).css('overflow-wrap','break-word').animate({
            			width: col_8
            		}, {
            			duration: 1000, 
            			queue: false
            		});
            		
            		$("#comments-body-"+name).animate({
            			width: col_4,
            			paddingRight: "15px",
            			paddingLeft: "15px",
            		}, {
            			duration: 1000, 
            			queue: false,
            			start: function () {
            				$("#comments").off('click');
            				$("#comments-body-"+name).css('display','block');
            			},
            			step: function () {
            				$(this).css('height',$("#post-card-"+name).height()); 
            			},
            			complete: function () {
            				$("#comments").on('click');
            				$("#comments-body-"+name).prop('active', 'true');
            			}
            		});
            	} else if (place == "true") {
            		$("#comments-body-"+name).animate({
            			paddingRight: "0px",
            			paddingLeft: "0px",
            			width: "0%"
            		}, {
            			duration: 1000, 
            			queue: false,
            			start: function () {
            				$("#comments").off('click');
            			},
            			step: function () {
            				$(this).css('height',$("#post-card-"+name).height()); 
            			},
            			complete: function () {
            				$("#comments-body-"+name).css('display','none');
            				$("#comments-body-"+name).prop('active', 'false');
            				$("#comments").on('click');
            			}
            		});
            		$("#post-card-"+name).animate({
            			width: col_12
            		}, {
            			duration: 1000, 
            			queue: false
            			
            		});
            	}
            });
    	});
        </script>
    </div>
EOT;
            header('Content-type: text/html');
            exit(json_encode(array('success' => true, 'value' => base64_encode($html))));
    /*____           __     __  ___   ____     ___    ____        _   _    ___    _____   ___    ____   _____   ____  
     / ___|          \ \   / / |_ _| / ___|   / _ \  |  _ \      | \ | |  / _ \  |_   _| |_ _|  / ___| | ____| / ___| 
     \___ \   _____   \ \ / /   | |  \___ \  | | | | | |_) |     |  \| | | | | |   | |    | |  | |     |  _|   \___ \ 
      ___) | |_____|   \ V /    | |   ___) | | |_| | |  _ <      | |\  | | |_| |   | |    | |  | |___  | |___   ___) |
     |____/             \_/    |___| |____/   \___/  |_| \_\     |_| \_|  \___/    |_|   |___|  \____| |_____| |____/ 
    */
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
            		$st = $conn->prepare("SELECT * FROM `notices`");
            		$st->execute();
            		$count = $st->rowCount();
            		$data = $st->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    exit(json_encode(array('success'=>false,'value'=>'unknown')));
                }
        		error_log('----------------  DATA --------------'.PHP_EOL.print_r($data,true).PHP_EOL.PHP_EOL.PHP_EOL,3,"logs/spesh.log");
        		$inner = "";
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
                           error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,"logs/noticesfunc.log");
                        } else {
                            throw $e;
                        }
        			    continue;
        			}
        			try {
            			$sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster'");
                        //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                        $sto->execute();
                        //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                        $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
        			} catch (Exception $e) {
        			    if ($e instanceof PDOException || $e instanceof Exception) {
                           error_log("Exception on line " + $e->getLine() + ": " + $e->getMessage() + PHP_EOL,3,"logs/noticesfunc.log");
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                           <strong class="card-title">
EOT;
                                $inner .= $title . <<<EOT
                                            </strong>
                                        </div> 
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <span style="cursor:pointer;" class="float-right">
                                                <a id="remove-notice" name="$id">
                                                    &nbsp&nbsp<i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                            <strong>
                                                <span class="
EOT;
                                $inner .=  $badge . " float-right mt-1\">" . ucfirst($poster) . <<<EOT
                                                </span>
                                            </strong>
                                        </div>
                                    </div>
                                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                                    <span>
                                        <small>$since</small>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
EOT;
                            $inner .= $content . <<<EOT
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
        exit(json_encode(array('success'=>true,'value'=>base64_encode($html))));    
    /*____    _____      _      _____   _____       _   _    ___    _____   ___    ____   _____   ____  
     / ___|  |_   _|    / \    |  ___| |  ___|     | \ | |  / _ \  |_   _| |_ _|  / ___| | ____| / ___| 
     \___ \    | |     / _ \   | |_    | |_        |  \| | | | | |   | |    | |  | |     |  _|   \___ \ 
      ___) |   | |    / ___ \  |  _|   |  _|       | |\  | | |_| |   | |    | |  | |___  | |___   ___) |
     |____/    |_|   /_/   \_\ |_|     |_|         |_| \_|  \___/    |_|   |___|  \____| |_____| |____/ 
    */
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
            		$st = $conn->prepare("SELECT * FROM `notices`");
            		$st->execute();
            		$count = $st->rowCount();
            		$data = $st->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    exit(json_encode(array('success'=>false,'value'=>'unknown')));
                }
        		error_log('----------------  DATA --------------'.PHP_EOL.print_r($data,true).PHP_EOL.PHP_EOL.PHP_EOL,3,"logs/spesh.log");
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
                           error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,"logs/noticesfunc.log");
                        } else {
                            throw $e;
                        }
        			    //continue;
        			}
        			try {
            			$sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster'");
                        //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                        $sto->execute();
                        //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                        $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
        			} catch (Exception $e) {
        			    if ($e instanceof PDOException || $e instanceof Exception) {
                           error_log("Exception on line " + $e->getLine() + ": " + $e->getMessage() + PHP_EOL,3,"logs/noticesfunc.log");
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
                    
                    $com = $conn->prepare("SELECT * FROM `comments` WHERE `notice_id`='$id'");
                    $com->execute();
                    $comment_count = $com->rowCount();
                    if($comment_count > 0) {
                        $comments = $con->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $comment_count = 0;
                    }
                    $inner = <<<EOT
                    <div id="$id" class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                           <strong class="card-title">
EOT;
                                $inner .= $title . <<<EOT
                                            </strong>
                                        </div> 
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <strong>
                                                <span class="
EOT;
                                $inner .=  $badge . " float-right mt-1\">" . ucfirst($poster) . <<<EOT
                                                </span>
                                            </strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <span>
                                                <small>$since</small>
                                                <style>
                                                    .quantity {
                                                        border-radius: 100px;
                                                        background: #dc3545;
                                                        color : white;
                                                        padding : 1px 6px; 
                                                    }
                                                </style>
                                                <small><span class="float-right" style="cursor:pointer;">Comments <span class="quantity"><bold></bold></span></span></small>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
EOT;
                                    $inner .= $content . <<<EOT
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
        exit(json_encode(array('success'=>true,'value'=>base64_encode($html))));    
    /*_   _   ____    ____       _      _____   _____       _   _    ___    _____   ___    ____   _____   ____  
     | | | | |  _ \  |  _ \     / \    |_   _| | ____|     | \ | |  / _ \  |_   _| |_ _|  / ___| | ____| / ___| 
     | | | | | |_) | | | | |   / _ \     | |   |  _|       |  \| | | | | |   | |    | |  | |     |  _|   \___ \ 
     | |_| | |  __/  | |_| |  / ___ \    | |   | |___      | |\  | | |_| |   | |    | |  | |___  | |___   ___) |
      \___/  |_|     |____/  /_/   \_\   |_|   |_____|     |_| \_|  \___/    |_|   |___|  \____| |_____| |____/ 
    */
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
            		$st = $conn->prepare("SELECT * FROM `notices`");
            		$st->execute();
            		$count = $st->rowCount();
            		$data = $st->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    exit(json_encode(array('success'=>false,'value'=>'unknown')));
                }
        		error_log('----------------  DATA --------------'.PHP_EOL.print_r($data,true).PHP_EOL.PHP_EOL.PHP_EOL,3,"logs/spesh.log");
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
                           error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,"logs/noticesfunc.log");
                        } else {
                            throw $e;
                        }
        			    continue;
        			}
        			try {
            			$sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster'");
                        //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                        $sto->execute();
                        //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                        $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
        			} catch (Exception $e) {
        			    if ($e instanceof PDOException || $e instanceof Exception) {
                           error_log("Exception on line " + $e->getLine() + ": " + $e->getMessage() + PHP_EOL,3,"logs/noticesfunc.log");
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                           <strong class="card-title">
EOT;
                                $inner .= $title . <<<EOT
                                            </strong>
                                        </div> 
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <span style="cursor:pointer;" class="float-right">
                                                <a id="remove-notice" name="$id">
                                                    &nbsp&nbsp<i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                            <strong>
                                                <span class="
EOT;
                                $inner .=  $badge . " float-right mt-1\">" . ucfirst($poster) . <<<EOT
                                                </span>
                                            </strong>
                                        </div>
                                    </div>
                                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                                    <span>
                                        <small>$since</small>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
EOT;
                            $inner .= $content . <<<EOT
                            </div>
                        </div>
                    </div>
                </div>
EOT;
                $body = $inner . $body;
            }
        $html .= $body . '</div></div>';
        header('Content-type: text/html');
        exit(json_encode(array('success' => true, 'value' => base64_encode($html))));
    } else if ($_REQUEST['message'] == "NOTICE_UPDATE" && $_SESSION['priv']['post'] == "false") {
            $html = <<<EOT
            		<div class="col-md-12">
            			<div class="overview-wrap">
            				<h2 class="title-1">Notices</h2>
            			</div><br />
EOT;
                	try {
            		$st = $conn->prepare("SELECT * FROM `notices`");
            		$st->execute();
            		$count = $st->rowCount();
            		$data = $st->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    exit(json_encode(array('success'=>false,'value'=>'unknown')));
                }
        		error_log('----------------  DATA --------------'.PHP_EOL.print_r($data,true).PHP_EOL.PHP_EOL.PHP_EOL,3,"logs/spesh.log");
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
                           error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,"logs/noticesfunc.log");
                        } else {
                            throw $e;
                        }
        			    continue;
        			}
        			try {
            			$sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster'");
                        //if(!($sto->execute())) {throw new Exception("Couldn't execute"); continue;}
                        $sto->execute();
                        //if(!($sto->fetchColumn())) {throw new Exception("Couldn't fetch"); continue;}
                        $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
        			} catch (Exception $e) {
        			    if ($e instanceof PDOException || $e instanceof Exception) {
                           error_log("Exception on line " + $e->getLine() + ": " + $e->getMessage() + PHP_EOL,3,"logs/noticesfunc.log");
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                           <strong class="card-title">
EOT;
                                $inner .= $title . <<<EOT
                                            </strong>
                                        </div> 
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <span style="cursor:pointer;" class="float-right">
                                                <a id="remove-notice" name="$id">
                                                    &nbsp&nbsp<i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                            <strong>
                                                <span class="
EOT;
                                $inner .=  $badge . " float-right mt-1\">" . ucfirst($poster) . <<<EOT
                                                </span>
                                            </strong>
                                        </div>
                                    </div>
                                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                                    <span>
                                        <small>$since</small>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
EOT;
                            $inner .= $content . <<<EOT
                            </div>
                        </div>
                    </div>
                </div>
EOT;
                $body = $inner . $body;
            }
        header('Content-type: text/html');
        exit(json_encode(array('success'=>true,'value'=>base64_encode($html))));
    /*_   _   _____  __        __      ____     ___    ____    _____ 
     | \ | | | ____| \ \      / /     |  _ \   / _ \  / ___|  |_   _|
     |  \| | |  _|    \ \ /\ / /      | |_) | | | | | \___ \    | |  
     | |\  | | |___    \ V  V /       |  __/  | |_| |  ___) |   | |  
     |_| \_| |_____|    \_/\_/        |_|      \___/  |____/    |_|  
    */
    } else if ($_POST['message'] == "NOTICE_POST") {
        $notice = base64_decode($_POST['data']);
        $datetime = date("Y-m-d H:i:s");
        $user_can_post = $_SESSION['priv']['post'];
        $type = "notice";
        if($user_can_post) {
            header('Content-type: text/html');
            try {
                $st = $conn->prepare("INSERT INTO `notices` (date, posted_by, content, type) VALUES ('$datetime', '$name', '$notice', '$type')");
                $st->execute();
                echo json_encode(array('success' => true, 'value' => 'Posted'));
            } catch (PDOException $e) {
                echo json_encode(array('success'=>false,'value'=>"Exception"));
            }
        } else {
            header('Content-type: text/html');
            echo json_encode(array('success'=>false,'value'=>"RGT_CNT_POST"));
        }
    } else if ($_POST['message'] == "ROSTER_POST") {
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
            $st = $conn->prepare("SELECT * FROM `staff`");
            $st->execute();
            $staff = $st->fetchAll(PDO::FETCH_ASSOC);
            error_log(print_r($staff, true),3,"logs/noticesfunc.log");
            $staffCount = $st->rowCount();
        } catch (PDOException $e) {
            error_log("?",3,"logs/noticesfunc.log");
            error_log($e,3,"logs/noticesfunc.log");
        }
        $body = "";
        
        for ($count = 0; $count < $staffCount; $count++) {
            $nname = ucfirst($staff[$count]['uname']);
            error_log(print_r($nname),3,"logs/noticesfunc.log");
            $body .= "<tr><td>$nname</td>";
            for ($x = 0; $x < 7; $x++) {
                $name_start = $roster[$days[$x]][$nname]["start"];
                $name_finish = $roster[$days[$x]][$nname]["finish"];
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
        $css = <<<EOT
            <style> .m-b-30{margin-bottom:30px}.table{margin:0}.table-responsive{padding-right:1px}.table-responsive .table--no-card{-webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px; -webkit-box-shadow:0 2px 5px 0 rgba(0,0,0,.1); -moz-box-shadow:0 2px 5px 0 rgba(0,0,0,.1); box-shadow:0 2px 5px 0 rgba(0,0,0,.1)}.table-earning thead th{background:#333; font-size:16px; color:#fff; vertical-align:middle; font-weight:400; text-transform:capitalize; line-height:1; padding:22px 40px; white-space:nowrap}.table-earning thead th.text-right{padding-left:15px; padding-right:65px}.table-earning tbody td{color:gray; padding:12px 40px; white-space:nowrap}.table-earning tbody td.text-right{padding-left:15px; padding-right:65px}.table-earning tbody tr:hover td{color:#555; cursor:pointer}.table-bordered{border:1px solid #dee2e6}.table-bordered td,.table-bordered th{border:1px solid #dee2e6}.table-bordered thead td,.table-bordered thead th{border-bottom-width:2px}@media(max-width:575.98px){.table-responsive-sm{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-sm>.table-bordered{border:0}}@media(max-width:767.98px){.table-responsive-md{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-md>.table-bordered{border:0}}@media(max-width:991.98px){.table-responsive-lg{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-lg>.table-bordered{border:0}}@media(max-width:1199.98px){.table-responsive-xl{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-xl>.table-bordered{border:0}}.table-responsive{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive>.table-bordered{border:0} </style>  
EOT;
        $notice = json_encode(array(
            'title' => 'Roster for week starting ' . $roster['startDate'], 
            'content' => base64_encode($html)
        ));
        
        $mail = json_encode(array(
            'title' => 'Roster for week starting ' . $roster['startDate'], 
            'content' => base64_encode($css . $html), 
            'comments' => $comment
        ));
        
        $datetime = date("Y-m-d H:i:s");
        $user_can_post = $_SESSION['priv']['post'];
        $type = "roster";
        if($user_can_post) {
            $st = $conn->prepare("INSERT INTO `notices` (date, posted_by, content, type) VALUES ('$datetime', '$name', '$notice', '$type')");
            $st->execute();
            email('roster', $mail, $roster['startDate']);
            //header('Content-type: text/html');
            echo json_encode(array(
                'success'=>true,
                'value'=>"Posted"
            ));
        } else {
            header('Content-type: text/html');
            echo json_encode(array(
                'success' => false,
                'value' => "RGT_CNT_POST"
            ));
        }
    } else if ($_POST['message'] == "NTC_RM") {
        $id = $_POST['value'];
        header('Content-type: text/html');
        try {
            $st = $conn->prepare("DELETE FROM `notices` WHERE `id`='$id'");
            $st->execute();
            echo json_encode(array(
                'success' => true, 
                'value' => '1'
            ));
        } catch (PDOException $e) {
            echo json_encode(array(
                'success' => false, 
                'value' => '0'
            ));
        }
    } else {
        header('Content-type: text/html');
        exit(json_encode(array('success'=>false,'value'=>"Unknown")));;
    }
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>