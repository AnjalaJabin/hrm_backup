<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="">
<meta name="author" content="Corbuz">
<link rel="icon" href="https://www.corbuz.com/app/uploads/logo/favicon/corbuz-favicon.png" >
<!-- Title -->
<title><?php echo $title;?></title>

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
<body class="img-cover" style="background:#ece7e5;">
<div class="container-fluid">
  <div class="sign-form">
    <div class="row">
      <div class="col-md-4 offset-md-4 px-3">
        <div class="box b-a-0">
          <div class="p-2 text-xs-center">
            <h5>Find your account</h5>
          </div>
          <form class="form-material" action="<?php echo site_url();?>forgot_password/send_mail/" method="post" name="xin-form" id="xin-form">
            <div class="form-group mb-0">
              <input type="text" class="form-control" name="iemail" id="iemail" placeholder="Enter your email...">
            </div>
            <div class="p-2 form-group mb-0">
              <button type="submit" class="btn btn-purple btn-block text-uppercase">Reset</button>
            </div>
          </form>
           <p class="p-2 text-xs-center"><a href="<?php echo site_url();?>"><i class="fa fa-lock"></i> Remeber Password?</a></p>
        </div>
      </div>
    </div>
  </div>
 
</div>

<!-- Vendor JS --> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-1.12.3.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/tether/js/tether.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/toastr/toastr.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	toastr.options.closeButton = true;
	toastr.options.progressBar = true;
	toastr.options.timeOut = 3000;
	toastr.options.positionClass = "toast-bottom-right";
	
	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=forgot_password&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					$('#iemail').val(''); // To reset form fields
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});
</script>
</body>
</html>