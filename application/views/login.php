<?php
$cookie_name = "myhrmusername";

if(isset($_COOKIE[$cookie_name])) {
    
    $session_data = unserialize($_COOKIE[$cookie_name]);
    
    $session_data = array(
	'user_id' => $session_data['user_id'],
	'username' => $session_data['username'],
	'email' => $session_data['email'],
	'root_id' => $session_data['root_id'],
	);
	
	$this->session->set_userdata('username', $session_data);
    
    $_SESSION['user_id'] = $session_data['user_id'];
    $_SESSION['root_id'] = $session_data['root_id'];
    
    if(isset($_REQUEST['next']))
    {
        if(!empty($_REQUEST['next']))
        {
            session_start();
            $_SESSION['next'] = $_REQUEST['next'];
            header('location:'.$_REQUEST['next']);
        }
    }
    else
    {
        header('location:./dashboard');
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
    <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!-- Website Font style -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
		
		<!-- Google Fonts -->
		<link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
		<link rel="icon" href="<?php echo base_url();?>uploads/logo/favicon/favicon.ico" >
		
		<!-- Vendor CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/themify-icons/themify-icons.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/toastr/toastr.min.css">
		
		<style>
		    /*
/* Created by Filipe Pina
 * Specific styles of signin, register, component
 */
/*
 * General styles
 */

body, html{
     height: 100%;
 	background-repeat: no-repeat;
 	background-color: #d3d3d3;
 	font-family: 'Oxygen', sans-serif;
}

.main{
 	margin-top: 80px;
}

h1.title { 
	font-size: 50px;
	font-family: 'Passion One', cursive; 
	font-weight: 400; 
}

hr{
	width: 10%;
	color: #fff;
}

.form-group{
	margin-bottom: 15px;
}

label{
	margin-bottom: 5px;
	font-weight: 600;
}
.login-form-text {
    color : #00315F;
    font-weight: 700;
    margin-bottom: 30px;
}

input,
input::-webkit-input-placeholder {
    font-size: 11px;
    padding-top: 3px;
}

.main-login{
 	background-color: #fff;
    /* shadows and rounded borders */
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);

}

.main-center{
 	margin-top: 30px;
 	margin: 0 auto;
 	max-width: 500px;
    padding: 25px 80px;

}

.login-button{
	margin-top: 20px;
	background-color: #20A1DE;
	border-color: #20A1DE;
	 transition: .3s all ease-in;
}
.login-button:hover{
	background-color: #004566;
	border-color: #004566;
}
.btn-warning.active, .btn-warning:active, .open>.dropdown-toggle.btn-warning{
    background-color: #004566;
	border-color: #004566;
}
.login-register{
	font-size: 14px;
	text-align: center;
	margin: 0px;
	
	
}

body { 
          background: #00315F url(../hrm/skin/img/bg-img-hrm.jpg) no-repeat center center fixed; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
        }

		</style>

		<title>Sign in.</title>
	</head>
	<body>
		<div class="container">
			<div class="row main">
				<div class="panel-heading">
	               <div class="panel-title text-center">
	               		<!--<a href="../"><img src="../hrm/skin/img/emso-white.png" alt="" class="responsive-img valign profile-image-login" style="max-width:250px;"></a>-->
	               		<p class="center login-form-text">&nbsp;</p>
	               	</div>
	            </div> 
				<div class="main-login main-center">
				    <div class="center" align="center">
				        <img src="../hrm/skin/img/emso-logo.png" alt=""class="responsive-img valign profile-image-login" style="max-width:110px;">
				    </div>
				    
					<form class="mb-1 log-form" method="post" name="hrm-form" id="hrm-form" data-redirect="dashboard" data-form-table="login" data-is-redirect="1">
					    <!--<form class="mb-1" method="post" action="./index/login/"> --> 
						<h2 align="center" class="center login-form-text">Sign in</h2>
						<div class="form-group">
							<label for="name" class="cols-sm-2 control-label">Username</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="iusername" id="iusername"  placeholder="Username" required/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="email" class="cols-sm-2 control-label">Password</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa" aria-hidden="true"></i></span>
									<input type="password" class="form-control" name="ipassword" id="ipassword"  placeholder="Password" required/>
								</div>
							</div>
						</div>

						<div class="form-group ">
							<button type="submit" class="btn btn-warning btn-lg btn-block login-button save" name="registerForm">Login</button>
						</div>
						
						<div class="login-register">
				            <a href="forgot_password">Forgot Password?</a>
				         </div>
						<br>
						<div class="login-register">
				            <a href="../register">Register</a>
				         </div>
						
					</form>
				</div>
			</div>
		</div>

	</body>
	
<!-- Vendor JS --> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-3.2.1.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/tether/js/tether.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/toastr/toastr.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	toastr.options.closeButton = true;
	toastr.options.progressBar = true;
	toastr.options.timeOut = 3000;
	toastr.options.preventDuplicates = true;
	toastr.options.positionClass = "toast-bottom-right";
});
</script>
<script type="text/javascript">var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript" src="<?php echo base_url();?>skin/js_module/xin_login.js?v=5.95.3619"></script>
</html>