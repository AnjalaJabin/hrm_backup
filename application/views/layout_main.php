<?php
$session = $this->session->userdata('username');
$system = $this->Xin_model->read_setting_info(1);
$layout = $this->Xin_model->system_layout();
?>
<?php $this->load->view('components/htmlheader');?>
<body class="large-sidebar <?php echo $layout;?> <?php echo $system[0]->system_skin;?> material-design ">
	<div class="wrapper g-body-color">
		<div class="preloader" style="display: none;"></div>
		<!-- Sidebar -->
		<div class="site-sidebar-overlay"></div>
		<?php 
		$this->load->view('components/left_menu');?>
        
		<!-- Header -->
		<?php $this->load->view('components/header');?>

		<div class="site-content g-site-content">
			<!-- Content -->
			<div class="content-area py-1 g-content-area">
				<div class="container-fluid">
					<h4><?php echo $breadcrumbs;?></h4>
					<ol class="breadcrumb no-bg mb-1">
						<li class="breadcrumb-item"><a href="<?php echo site_url()?>dashboard">Home</a></li>
						<li class="breadcrumb-item active"><?php echo $breadcrumbs;?></li>
					</ol>
                    <?php // get the required layout..?>
					<?php echo $subview;?>
				  </div>
			</div>
			<!-- Footer -->
			<?php 
			$this->load->view('components/footer');?>
		</div>
	</div>
<?php $this->load->view('components/htmlfooter');?>