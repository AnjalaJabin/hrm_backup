<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title;?></title>

<!-- Stylesheets -->
<link href='//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic|Roboto+Condensed:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/jobs/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/jobs/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/jobs/css/flexslider.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/jobs/css/style.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/jobs/css/responsive.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/themify-icons/themify-icons.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/animate.css/animate.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/jscrollpane/jquery.jscrollpane.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/waves/waves.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/chartist/chartist.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/switchery/dist/switchery.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/DataTables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/DataTables/Responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/DataTables/Buttons/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/DataTables/Buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/toastr/toastr.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/summernote/summernote.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/jquery-ui/jquery-ui.css">
<!-- Core CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/css/core.css">

<!--[if IE 9]>
		<script src="js/media.match.min.js"></script>
	<![endif]-->
</head>

<body>
<div id="main-wrapper">
  <header id="header" class="header-style-1">
    <div class="header-nav-bar">
      <div class="container"> 
        
        <!-- Logo -->
        <div class="css-table logo">
          <div class="css-table-cell"> <a href="<?php echo site_url();?>frontend/jobs/"> <img src="<?php echo base_url();?>uploads/logo/corbuz-logo.png" alt="Corbuz Careers" style="max-width:200px;"> </a> <!-- end .logo --> 
          </div>
        </div>
        
        <!-- Mobile Menu Toggle --> 
        <a href="#" id="mobile-menu-toggle"><span></span></a> 
        
        <!-- Primary Nav -->
        <nav>
          <ul class="primary-nav">
            <li> <a href="<?php echo site_url();?>dashboard/">Home</a> </li>
            <li class="active"> <a href="<?php echo site_url();?>frontend/jobs/">Jobs</a> </li>
          </ul>
        </nav>
      </div>
      <!-- end .container -->
      
      <div id="mobile-menu-container" class="container">
        <div class="login-register"></div>
        <div class="menu"></div>
      </div>
    </div>
    <!-- end .header-nav-bar -->
    <?php $job_designation = $this->Frontend_job_model->read_designation_information($designation_id);?>
    <?php $department = $this->Frontend_job_model->read_department_information($job_designation[0]->department_id);?>
    <div class="header-page-title">
      <div class="container">
        <h1><?php echo $job_title?> <small>(<?php echo $department[0]->department_name;?>)</small></h1>
        <ul class="breadcrumbs">
           <li> <a href="<?php echo site_url();?>dashboard/">Home</a> </li>
           <li> <a href="<?php echo site_url();?>frontend/jobs/">Jobs</a> </li>
          <li class="active"><a href="#"><?php echo $job_title?></a></li>
        </ul>
      </div>
    </div>
  </header>
  <!-- end #header -->
  <div id="page-content">
    <div class="container">
      <div class="row">
        <div class="col-md-4 page-sidebar">
          <aside>
            <div class="widget sidebar-widget white-container candidates-single-widget">
              <div class="widget-content">
                <h5 class="bottom-line">Job Details</h5>
                <table>
                  <tbody>
                    <tr>
                      <td>ID</td>
                      <td>#<?php echo $job_id;?></td>
                    </tr>
                    <tr>
                      <td>Functional Area</td>
                      <td><?php echo $job_designation[0]->designation_name;?></td>
                    </tr>
                    <tr>
                      <td>Type</td>
                      <td>Employer</td>
                    </tr>
                    <tr>
                      <td>Role</td>
                      <td><?php echo $job_title;?></td>
                    </tr>
                    <tr>
                      <td>Gender</td>
                      <td><?php echo $gender;?></td>
                    </tr>
                    <tr>
                      <td>Years of Experience</td>
                      <td><?php echo $minimum_experience;?></td>
                    </tr>
                    <tr>
                      <td>Vacancy</td>
                      <td><?php echo $job_vacancy;?></td>
                    </tr>
                    <tr>
                      <td>Apply Before</td>
                      <td><?php echo date('M d, Y', strtotime($date_of_closing));?></td>
                    </tr>
                    <tr>
                      <td>Posted Date</td>
                      <td><?php echo date('M d, Y', strtotime($created_at));?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </aside>
        </div>
        <!-- end .page-sidebar -->
        <?php $jtype = $this->Frontend_job_model->read_job_type_information($job_type_id);?>
        <div class="col-md-8 page-content">
          <div class="clearfix mb30 hidden-xs"> <a href="<?php echo site_url();?>frontend/jobs/" class="btn btn-gray pull-left">Back to Listings</a> </div>
          <div class="jobs-item jobs-single-item">
            <div class="clearfix visible-xs"></div>
            <div class="date"><?php echo date("j", strtotime($created_at));?> <span><?php echo date("M", strtotime($created_at));?></span></div>
            <h6 class="title"><a href="#"><?php echo $job_title;?></a></h6>
            <span class="meta"><?php echo $job_designation[0]->designation_name;?> > <?php echo $department[0]->department_name;?></span> <span class="top-btns"> <span class="label-primary"><?php echo $jtype[0]->type;?></span></span>
            <br> <?php echo htmlspecialchars_decode($long_description);?>
            <hr>
            <div class="clearfix">
              <button type="submit" class="btn btn-default" data-toggle="modal" data-target=".apply-job" data-job_id="<?php echo $job_id;?>">Apply for this Job</button>
            </div>
          </div>
        </div>
        <!-- end .page-content --> 
      </div>
    </div>
    <!-- end .container --> 
  </div>
  <!-- end #page-content -->
  <div class="modal fade apply-job" id="apply-job" tabindex="-1" role="dialog" aria-labelledby="apply-job" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_modal"></div>
  </div>
</div>
  <footer id="footer">
   <div class="copyright">     
   	<div class="container"> 
    <p><?php echo date('Y');?>  © Corbuz Careers</p>
    </div>
  </div>
</footer>
  <!-- end #footer -->
</div>
<!-- end #main-wrapper --> 
<!-- Vendor JS --> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-1.12.3.min.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/jquery.ba-outside-events.min.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/jquery.responsive-tabs.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/jquery-ui-1.10.4.custom.min.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/jquery.inview.min.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/script.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery/jquery-1.12.3.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/tether/js/tether.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/detectmobilebrowser/detectmobilebrowser.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jscrollpane/jquery.mousewheel.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jscrollpane/mwheelIntent.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jscrollpane/jquery.jscrollpane.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/waves/waves.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/chartist/chartist.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/switchery/dist/switchery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/DataTables/js/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/DataTables/js/dataTables.bootstrap4.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/DataTables/Responsive/js/dataTables.responsive.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/DataTables/Responsive/js/responsive.bootstrap4.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/DataTables/Buttons/js/buttons.colVis.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery-fullscreen-plugin/jquery.fullscreen-min.js"></script> 
<!-- JS --> 
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/toastr/toastr.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/summernote/summernote.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/js/app.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/js/demo.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	toastr.options.closeButton = true;
	toastr.options.progressBar = false;
	toastr.options.timeOut = 3000;
	toastr.options.positionClass = "toast-bottom-right";
			
	$("#apply_job").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 6);
		fd.append("type", 'apply_job');
		fd.append("data", 'apply_job');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					$('.apply-form').fadeOut('slow');
					$('#apply_job')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	
	// get data
	$('.apply-job').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var job_id = button.data('job_id');
		var modal = $(this);
	$.ajax({
		url : "<?php echo site_url("frontend/jobs/apply") ?>",
		type: "GET",
		data: 'jd=1&is_ajax=app_job&mode=modal&data=apply_job&type=apply_job&job_id='+job_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
	});
	});
}); // jquery loaded
</script>
</body>
</html>