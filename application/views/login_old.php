<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="">
<meta name="author" content="Corbuz">
<link rel="icon" href="<?php echo base_url();?>uploads/logo/favicon/corbuz-favicon.png" >
<!-- Title -->
<title><?php echo $title; ?></title>

<!-- Vendor CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/bootstrap4/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/themify-icons/themify-icons.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/toastr/toastr.min.css">

<!-- Core CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/css/core.css">
<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="auth-bg">
<div class="auth">
    <div class="auth-header">
            <div class="mb-2">
                <a href="../"> <img src="<?php echo base_url();?>uploads/logo/corbuz-logo2-white.png" title="" style="max-width:300px;"> </a>
            </div>
        <h6></h6>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <form class="mb-1" method="post" name="hrm-form" id="hrm-form" data-redirect="dashboard?module=dashboard" data-form-table="login" data-is-redirect="1">
                    <!-- <form class="mb-1" method="post" action="./index/login/"> --> 
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="iusername" id="iusername" placeholder="Username">
                            <div class="input-group-addon"><i class="ti-user"></i></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                        <input type="password" class="form-control" name="ipassword" id="ipassword" placeholder="Password">
                            <div class="input-group-addon"><i class="ti-key"></i></div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="float-xs-right">
                            <a class="text-white font-90" href="<?php echo site_url('forgot_password');?>">Forgot password?</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger btn-block">Sign in</button>
                    </div>
                </form>
                <div class="row">
                <div class="col-md-12" align="center">
                <?php echo date('Y');?>  © Corbuz
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Vendor JS --> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-3.2.1.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/tether/js/tether.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/toastr/toastr.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	toastr.options.closeButton = <?php //echo $system[0]->notification_close_btn;?>;
	toastr.options.progressBar = <?php //echo $system[0]->notification_bar;?>;
	toastr.options.timeOut = 3000;
	toastr.options.preventDuplicates = true;
	toastr.options.positionClass = "<?php //echo $system[0]->notification_position;?>";
});
</script>
<script type="text/javascript">var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript" src="<?php echo base_url();?>skin/js_module/xin_login.js"></script>
</body>
</html>