<?php
class notice extends system {
    public function __construct($session) {
        system::__construct($session);
    }

    public function build(array $options = [], bool $notices_only = false) {
        $this->options = $options; $this->notices_only = $notices_only;
    }

    private function generate() {
        #region
        $MODAL = <<<HTML
            <div class="modal fade show" id="newPostModal" tabindex="-100" data-backdrop="false" role="dialog" aria-labelledby="largeModalLabel" style="display: none;">
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
                        <button type="button" id="submit" class="btn btn-primary" type="submit" data-dismiss="modal" form="newpost">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
HTML;
        $ADMIN_HEAD = "<div id=\"notices\" class=\"section__content section__content--p30\"> <div class=\"container-fluid\" id=\"content\"> <div class=\"col-md-12\"> <div class=\"overview-wrap\"> <h2 class=\"title-1\">Notices</h2> <button class=\"au-btn au-btn-icon au-btn--blue\" data-toggle=\"modal\" data-target=\"#newPostModal\"> <i class=\"zmdi zmdi-plus\"></i>new post</button> </div><br/>";
        $USER_HEAD = "<div id=\"notices\" class=\"section__content section__content--p30\"><div class=\"container-fluid\" id=\"content\"><div class=\"col-md-12\"><div class=\"overview-wrap\"><h2 class=\"title-1\">Notices</h2></div><br />";

        global $conn, $error;

        try {
            $st = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$user' AND `store_id`='$store_id'");
            $st->execute();
            $user_priv = json_decode(stripslashes($st->fetchColumn()),true);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }
        }
        if ($user_priv['post'] === "true") {
            $html = $ADMIN_HEAD;
        } else {
            $html = $USER_HEAD;
        }
        try {
            $st = $conn->prepare("SELECT * FROM `notices` WHERE `store_id`='$store_id'");
            $st->execute();
            $count = $st->rowCount();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }
        }
        $body = "";
        foreach ($data as $key => $value) {
            $id = $value['id'];
            try{
                $poster = $value['posted_by'];
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
                    $error->add_error("%cError: %cUnknown type while generating the notices page.", ['font-weight:bold;', 'color:red;'], true);
                }
                if($title === null || $content === null) {throw new Exception("No content");}
                $since = $this->humanTiming($value['date']);
            } catch (Exception $e){ 
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
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
                        error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
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
            if(!$this->notices_only) {
                if($user_priv['edit'] === "true") {
                    $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-4\"> <span style=\"cursor:pointer;\" class=\"float-right\"> <a id=\"remove-notice\" name=\"" . $id . "\"> &nbsp;&nbsp;<i class=\"fas fa-times\"></i> </a> </span> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div></div></div></div>" . $body;
                } else {
                    $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-4\"> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div></div></div></div>" . $body;
                }
            } else { //Notices only, don't need to end the 3 header divs
                if($user_priv['edit'] === "true") {
                    $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-4\"> <span style=\"cursor:pointer;\" class=\"float-right\"> <a id=\"remove-notice\" name=\"" . $id . "\"> &nbsp;&nbsp;<i class=\"fas fa-times\"></i> </a> </span> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div>" . $body;
                } else {
                    $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-4\"> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div></div></div></div>" . $body;
                }
            }
        }
        $uppername = ucfirst($user);
        if(!isset($this->options["NOTICE_UPDATE_ALERT_TITLE"])) {
            $NOTICE_UPDATE_ALERT_TITLE = $uppername . " has created a notice.";
        }
        if(!(isset($this->options["NOTICE_UPDATE_ALERT_CONTENT"]))) {
            $NOTICE_UPDATE_ALERT_CONTENT = "b.title"; //b.title is for the minified JS. 'b' is subject to change!
        } else {
            $NOTICE_UPDATE_ALERT_CONTENT = "'" . $NOTICE_UPDATE_ALERT_CONTENT . "'"; //used in the event that we use a string as the code is escaped for the use of object.title (needs to be escaped otherwise it'll be strung)
        }
        if(!$this->notices_only) {
            if ($user_priv['edit'] === "true" && $user_priv['post'] === "true") {
                $html .= $body . $MODAL . "</div></div></div>" . "<script> $(document).ready(function(){ $(\"button#submit\").click(function(c){c.preventDefault();var a=$(\"#newpost\").serializeArray();var b={};$(a).each(function(d,e){b[e.name]=e.value});$.ajax({type:\"POST\",url:\"backend/ajax/noticesfunc.php\",dataType:\"json\",data:{message:\"NOTICE_POST\",data:window.btoa(JSON.stringify(b))},success:function(f){var g={proto:\"UP_NOTI\"},d={proto:\"SN_NOTI\",title:\"" . $NOTICE_UPDATE_ALERT_TITLE . "\",body:" . $NOTICE_UPDATE_ALERT_CONTENT . "};window.client.send(JSON.stringify(g));window.client.send(JSON.stringify(d))},error:function(d){}});$(\"#newPostModal\").modal(\"hide\")});$(\"body\").on(\"click\",\"#remove-notice\",function(a){var b=this.name;$(\"div#\"+b).remove();$.ajax({type:\"POST\",url:\"backend/ajax/noticesfunc.php\",dataType:\"json\",data:{message:\"NTC_RM\",value:b},success:function(c){},error:function(c){disError(\"Failed to remove notice.\",true)}})})});</script>" . "</div>";
            } else {
                $html .= $body . "</div></div></div></div>";
            }
        } else {
            $html = $body;
        }
        return $html;
        #endregion
    }

}
?>