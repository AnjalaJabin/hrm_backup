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

<!--[if IE 9]>
		<script src="js/media.match.min.js"></script>
	<![endif]-->
</head>

<body>
<div id="main-wrapper">
  <header id="header" class="header-style-1">
    <!-- end .container --><!-- end .header-login --><!-- Header Login --><!-- end .header-top-bar -->
    
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
            <li class=""> <a href="<?php echo site_url();?>dashboard">Home</a> </li>
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
    
    <div class="header-page-title">
      <div class="container">
        <h1>Available Jobs <small>(<?php $jobs = $this->Frontend_job_model->get_jobs(); echo $jobs->num_rows()?>)</small></h1>
        <ul class="breadcrumbs">
          <li><a href="<?php echo site_url();?>dashboard">Home</a></li>
          <li><a href="<?php echo site_url();?>frontend/jobs/">Jobs</a></li>
        </ul>
      </div>
    </div>
  </header>
  <!-- end #header -->
  
  <div id="page-content">
    <div class="container">
      <div class="row">        
        <div class="col-md-12 page-content">
          <div class="title-lines">
            <h3 class="mt0">Available Jobs</h3>
          </div>
          <?php foreach($all_jobs as $job) {?>
		  <?php $jtype = $this->Frontend_job_model->read_job_type_information($job->job_type); ?>
          <div class="jobs-item with-thumb">
            <div class="clearfix visible-xs"></div>
            <div class="date"><?php echo date("j", strtotime($job->created_at));?> <span><?php echo date("M", strtotime($job->created_at));?></span></div>
            <h6 class="title"><a href="<?php echo site_url();?>frontend/jobs/detail/<?php echo $job->job_id;?>/"><?php echo $job->job_title;?></a></h6>
            <?php $job_designation = $this->Frontend_job_model->read_designation_information($job->designation_id);?>
            <?php $department = $this->Frontend_job_model->read_department_information($job_designation[0]->department_id);?>
            <span class="meta"><?php echo $job_designation[0]->designation_name;?> > <?php echo $department[0]->department_name;?></span> <span class="top-btns"> <span class="label-primary"><?php echo $jtype[0]->type;?></span></span>
            <p class="description"><?php echo htmlspecialchars_decode($job->short_description);?> <a href="<?php echo site_url();?>frontend/jobs/detail/<?php echo $job->job_id;?>/">Read More</a></p>
          </div>
          <?php } ?>
        </div>
        <!-- end .page-content --> 
      </div>
    </div>
    <!-- end .container --> 
  </div>
  <!-- end #page-content -->
  
  <footer id="footer">
   <div class="copyright">     
   	<div class="container"> 
    <p><?php echo date('Y');?>  © Corbuz Careers</p>
    </div>
  </div>
</footer>
<!-- end #footer -->

</div>
<style type="text/css">
.active-job { color:#000; font-weight:bold; }
</style>
<!-- end #main-wrapper --> 
<!-- Scripts --> 
<script src="<?php echo base_url();?>skin/vendor/jobs/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="<?php echo base_url();?>skin/js/jquery-1.11.0.min.js"><\/script>')</script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/jquery.responsive-tabs.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/jquery.flexslider-min.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/jquery-ui-1.10.4.custom.min.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/jquery.inview.min.js"></script> 
<script src="<?php echo base_url();?>skin/vendor/jobs/js/script.js"></script>
</body>
</html>