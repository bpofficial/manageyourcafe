<?php

$LOG = "logs/notices.log";
include('../php/config.php');
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

if($_REQUEST['message'] == "REQ_PAGE") {
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
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                       <strong class="card-title">
EOT;
                            $inner .= $title . <<<EOT
                                        </strong>
                                    </div> 
                                    <div class="col-lg-4">
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
                                <div class="col-lg-12">
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
                                <div id="post-card-$id" class="col-lg-12" style="position:relative;">
EOT;
                        $inner .= $content . <<<EOT
                                </div>
                                <style>
                                    .show {
                                        display:block;
                                    }
                                    .hide {
                                        display:none;
                                    }
                                </style>
                                <div class="col-lg-4 hide" id="comments-body-$id">
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
        $('body').on('click', '#comments',function(e) {
            var name = $(this).attr('name');
            var style = $("#comments-body-"+name).prop('class');
            if(style == "col-lg-4 hide") {
                $("#post-card-"+name).animate({
                    width: "80%"
                },1000);
                $("#post-card-"+name).prop('class', 'col-lg-8');
                $("#comments-body-"+name).show("slide", {direction: "right"}, 1000, function() {
                    $("#comments-body-"+name).prop('class', 'col-lg-4 show');
                });
            } else if (style == "col-lg-4 show") {
                $("#comments-body-"+name).hide("slide", {direction: "right"}, 1000, function() {
                    $("#post-card-"+name).prop('class', 'col-lg-12');
                    $("#comments-body-"+name).prop('class', 'col-lg-4 hide');
                });
            }
        });
	});
    </script>
</div>
EOT;
    header('Content-type: text/html');
    exit(json_encode(array('success' => true, 'value' => base64_encode($html))));
}

?>