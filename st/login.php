<?php
session_start();
if(isset($_SESSION)) {
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<title>Staff Login</title>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" media="all">       
        <link href="cafesuite/css/master.css" rel="stylesheet" media="all">
        <link href="cafesuite/css/custom.css" rel="stylesheet" media="all">
    	<link rel="stylesheet" type="text/css" href="cafesuite/css/login/util.css">
    	<link rel="stylesheet" type="text/css" href="cafesuite/css/login/main.css">
        <link rel="icon" type="image/png" sizes="96x96" href="cafesuite/images/login/favicon.png">

        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        
        <link rel="manifest" href="cafesuite/images/login/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="cafesuite/images/login/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
    	
    	<div class="limiter">
    		<div class="container-login100">
    			<div class="wrap-login100 p-t-190 p-b-30">
    				<form name="login" id="login" class="login100-form validate-form">
    					<div class="login100-form-avatar">
    						<img src="cafesuite/images/login/avatar-01.png" alt="AVATAR">
    					</div>
    
    					<span class="login100-form-title p-t-20 p-b-25">
    						Stones Throw
    					</span>
                        <div class="text-center w-full p-t-0 p-b-0" id="error">
    					    
    					</div>
    					<div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required">
    						<input class="input100" type="text" id="user" autocomplete="name" form="login" name="user" placeholder="Username">
    						<span class="focus-input100"></span>
    						<span class="symbol-input100">
    							<i class="fa fa-user"></i>
    						</span>
    					</div>
    
    					<div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required">
    						<input class="input100" type="password" id="pass" autocomplete="current-password" name="pass" form="login" placeholder="Password">
    						<span class="focus-input100"></span>
    						<span class="symbol-input100">
    							<i class="fa fa-lock"></i>
    						</span>
    					</div>
    
    					<div class="container-login100-form-btn p-t-10">
    						<button id="login-submit" form="login" class="login100-form-btn" type="submit" value="Login">
    							Login
    						</button>
    					</div>
    
    					<div class="text-center w-full p-t-25 p-b-230">
    						<a href="#" class="txt1">
    							Forgot Username / Password?
    						</a>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </body>
    <script>
        $('form').submit(function() {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'backend/ajax/loginfunc.php',
                dataType: 'json',
                data:
                    {
                        message: "LOGIN_REQ",
                        data: $('form').serialize()
                    },
                success: function(result){
                    if(!result.success) {
                        document.getElementById('error').innerHTML = result.value;
                    } else {
                        window.location.replace("https://manageyour.cafe/st/dashboard");
                    }
                }, 
                error: function(){}
            });
        })
    </script>
</html>