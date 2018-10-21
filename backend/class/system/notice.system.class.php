<?php
class notice extends system {
    #region Instatiation
    public function __construct($session) {
        system::__construct($session);
    }

    public function build(array $options = [], bool $notices_only = false) {
        $this->options = $options; $this->notices_only = $notices_only;
        return $this->generate();
    }
    #endregion
    #region Builders
    private function generate() {
        #region
        #region Variables
        $html = <<<HTML
            <div id="newpost-wrapper">
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
                <div id="notices" class="section__content section__content--p30">
                    <div class="container-fluid" id="content">
                        <div class="col-md-12">
                            <div class="overview-wrap">
                                <h2 class="title-1">Notices</h2> 
                                *_NEWPOSTBUTTON_* 
                            </div>
                            <br/>
                            *_BODY_*
                        </div>
                    </div>
                </div>
                <script>
                    *_SCRIPT_*
                </script>
HTML;
        $newpostbutton = <<<HTML
            <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#newPostModal">
                <i class="zmdi zmdi-plus"></i>
                new post
            </button>
HTML;
        $full_script = <<<JS
            $(document).ready(function() {
                $("button#submit").click(function(c) {
                    c.preventDefault();
                    var a = $("#newpost").serializeArray();
                    var b = {};
                    $(a).each(function(d, e) {
                        b[e.name] = e.value
                    });
                    $.ajax({
                        type: "POST",
                        url: "backend/ajax/noticesfunc.php",
                        dataType: "json",
                        data: {
                            message: "NOTICE_POST",
                            data: window.btoa(JSON.stringify(b))
                        },
                        success: function(f) {
                            var g = {
                                    proto: "UP_NOTI"
                                },
                                d = {
                                    proto: "SN_NOTI",
                                    title: "*_NOTICE_UPDATE_ALERT_TITLE_*",
                                    body: "*_$NOTICE_UPDATE_ALERT_CONTENT_*"
                                };
                            window.client.send(JSON.stringify(g));
                            window.client.send(JSON.stringify(d))
                        },
                        error: function(d) {}
                    });
                    $("#newPostModal").modal("hide")
                });
                $("body").on("click", "#remove-notice", function(a) {
                    var b = this.name;
                    $("div#" + b).remove();
                    $.ajax({
                        type: "POST",
                        url: "backend/ajax/noticesfunc.php",
                        dataType: "json",
                        data: {
                            message: "NTC_RM",
                            value: b
                        }
                    })
                })
            });
JS;
        $edit_script = <<<JS
            $(function(){
                $("body").on("click", "#remove-notice", function(a) {
                    var b = this.name;
                    $("div#" + b).remove();
                    $.ajax({
                        type: "POST",
                        url: "backend/ajax/noticesfunc.php",
                        dataType: "json",
                        data: {
                            message: "NTC_RM",
                            value: b
                        }
                    })
                });
            });
JS;
        global $conn, $error;
        $success = true;
        #endregion
        #region User priv
        try {
            $st = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$this->user' AND `store_id`='$this->store_id'");
            $st->execute();
            $user_priv = json_decode($st->fetchColumn(),true);
            (!$success) ?? $success = true;
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            $success = false;
        }
        #endregion
        #region Gather notices
        try {
            $st = $conn->prepare("SELECT * FROM `notices` WHERE `store_id`='$this->store_id'");
            $st->execute();
            $count = $st->rowCount();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            (!$success) ?? $success = true;
        } catch (PDOException $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            $success = false;
        }
        #endregion
        #region Main generation
        $body = "";
        foreach ($data as $key => $value) {
            $id = $value['id'];
            #region Notice content
            try{
                $poster = $value['posted_by'];
                $type = $value['type'];
                if ($type == "notice") {
                    $content = json_decode(stripslashes($value['content']),true);
                    $title = $content['title'];
                    $content = "<p class=\"card-text\">" . $content['content'] . "</p>";
                } else {
                    $error->add_error("%cError: %cUnknown type while generating the notices page.", ['font-weight:bold;', 'color:red;'], true);
                }
                if($title === null || $content === null) {
                    $error->add_error("%cError: %cEmpty content.%c", ['font-weight:bold;', 'color:red;','color:black'], true);
                    continue;
                }
                $since = $this->humanTiming($value['date']);
            } catch (Exception $e){ 
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            }
            #endregion
            $com = $conn->prepare("SELECT * FROM `comments` WHERE `notice_id`='$id' AND `store_id`='$this->store_id'");
            $com->execute();
            $comment_count = $com->rowCount() || 0;
            ($comment_count == 0) ?? $comments = $con->fetchAll(PDO::FETCH_ASSOC);
            $data = array(
                "ID" => $id,
                "TITLE" => $title,
                "POSTER" => ucfirst($poster),
                "SINCE" => $since,
                "CONTENT" => $content,
                "REMOVE_PERMISSION" => $user_priv['edit'],
                "COMMENTS" => $comments
            );
            $body .= $this->buildTemplate($data);
        }
        #endregion
        #region Stupid notification stuff 
        // TODO: update it lol
        /*
            if(!isset($this->options["NOTICE_UPDATE_ALERT_TITLE"])) {
                $NOTICE_UPDATE_ALERT_TITLE = ucfirst($user) . " has created a notice.";
            }
            if(!(isset($this->options["NOTICE_UPDATE_ALERT_CONTENT"]))) {
                $NOTICE_UPDATE_ALERT_CONTENT = "b.title"; //b.title is for the minified JS. 'b' is subject to change!
            } else {
                $NOTICE_UPDATE_ALERT_CONTENT = "'" . $NOTICE_UPDATE_ALERT_CONTENT . "'"; //used in the event that we use a string as the code is escaped for the use of object.title (needs to be escaped otherwise it'll be strung)
            }
        */
        #endregion
        #region User logic and string replacements
        if ($user_priv['post']) {
            $html = strtr($html,
                array(
                    "*_NEWPOSTBUTTON_*" => $newpostbutton,
                    "*_BODY_*" => $body,
                    "*_SCRIPT_*" => $full_script
                )
            );
        } else {
            $html = strtr($html,
                array(
                    "*_NEWPOSTBUTTON_*" => "",
                    "*_BODY_*" => $body,
                    "*_SCRIPT_*" => $edit_script
                )
            );
        }
        #endregion
        return json_encode(array(
            "success" => $success,
            "value" => base64_encode($html),
            "errors" => $error->generate(),
            "time" => microtime(true) - $time,
            "user" => json_encode($user_priv)
        ));
        #endregion
    }

    private function buildTempate($data) {
        #region
        #region Variables
        $remove_template = <<<HTML
            <span style="cursor:pointer;" class="float-right"> 
                <a id="remove-notice" name="*_ID_*"> &nbsp;&nbsp;<i class="fas fa-times"></i> </a> 
            </span> 
HTML;
        $notice_template = <<<HTML
            <div id="*_ID_*"" class="row"> 
                <div class="col-12"> 
                    <div class="card" id="card-*_ID_*""> 
                        <div class="card-header"> 
                            <div class="row"> 
                                <div class="col-8"> 
                                    <strong class="card-title">
                                        *_TITLE_*
                                    </strong> 
                                </div>
                            <div class="col-4"> 
                                *_REMOVE_*
                                <strong> 
                                    <span class="*_BADGE_* float-right mt-1">
                                        *_POSTER_*
                                    </span> 
                                </strong> 
                            </div>
                            <div class="row"> 
                                <div class="col-lg-12 col-md-8 col-sm-6 col-xs-3"> 
                                    <span> 
                                        <small>
                                            *_SINCE_*
                                        </small> 
                                    </span> 
                                </div>
                            </div>
                        </div>
                        <div class="card-body"> 
                            <div class="row"> 
                                <div id="post-card-*_ID_*"" class="col-lg-12 col-md-8 col-sm-6 col-xs-3">
                                    *_CONTENT_*
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
HTML;
        $remove_template = str_replace("*_ID_*",$data['ID'],$remove_template);
        #endregion
        return strtr(
            $notice_template, 
            array(
                "*_ID_*" => $data['ID'],
                "*_TITLE_*" => $data['TITLE'],
                "*_POSTER_*" => $data['POSTER'],
                "*_REMOVE_*" => ($data['REMOVE_PERMISSION']) ? $remove_template : "",
                "*_SINCE_*" => $data['SINCE'],
                "*_CONTENT_*" => $data['CONTENT']
            )
        );
        #endregion
    }
    #endregion
    #region Utilities
    public function createPost($data) {
        global $conn, $error;
        $notice = base64_decode($data);
        $datetime = date("Y-m-d H:i:s");
        $user_can_post = "";
        $success = true;
        try {
            $st = $conn->prepare("SELECT `priv` FROM `staff` WHERE `uname`='$this->user' AND `store_id`='$this->store_id'");
            $st->execute();
            if($user_can_post) {
                try {
                    $conn->prepare("INSERT INTO `notices` (store_id, date, posted_by, content, type) VALUES ('$store_id','$datetime', '$name', '$notice', 'notice')")->execute();
                } catch (PDOException $e) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    $success = false;
                }
            } else {
                $success = false;
            }
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            $success = false;
        }
        return  json_encode(
            array(
                'success' => $success,
                'errors' => $error->generate()
            )
        );
    }

    public function removePost() {

    }

    public function updatePost() {

    }



}
?>