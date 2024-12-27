<?php
$session = $this->session->userdata('username');
$system = $this->Xin_model->read_setting_info(1);
$user_info = $this->Xin_model->read_user_info($session['user_id']);
$role_user = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
$role_resources_ids = explode(',',$role_user[0]->role_resources);

$subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
$subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
$subdomain_name; // Print the sub domain

$accounts_url = base_url();

if($system[0]->system_skin=='skin-default'){
	$cl_skin = 'light';
} else if($system[0]->system_skin=='skin-1'){
	$cl_skin = 'dark';
} else if($system[0]->system_skin=='skin-2'){
	$cl_skin = 'light';
} else if($system[0]->system_skin=='skin-3'){
	$cl_skin = 'light';
} else if($system[0]->system_skin=='skin-4'){
	$cl_skin = 'dark';
} else if($system[0]->system_skin=='skin-5'){
	$cl_skin = 'dark';
} else if($system[0]->system_skin=='skin-6'){
	$cl_skin = 'dark';
}

$session = $this->session->userdata('username');
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if(!isset($_COOKIE['myhrmusername'])) {
    header('');
}

if(empty($session)){ 
	header('');
} 


if($this->Xin_model->get_contact_group($session['user_id'])==0)
{
    $data = array(
	'name' => 'Friends',
	'user_id' => $session['user_id'],
	'created_at' => date('Y-m-d H:i:s'),
	);
	$result = $this->Xin_model->add_group($data);
	
	$data = array(
	'name' => 'Family',
	'user_id' => $session['user_id'],
	'created_at' => date('Y-m-d H:i:s'),
	);
	$result = $this->Xin_model->add_group($data);
	
	$data = array(
	'name' => 'Business',
	'user_id' => $session['user_id'],
	'created_at' => date('Y-m-d H:i:s'),
	);
	$result = $this->Xin_model->add_group($data);
}


if($user_info[0]->user_role_id==1) {

$re_url= site_url();
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if($this->Xin_model->get_all_companies()==0)
{
    if($actual_link!=$re_url."company") { header("location:".$re_url."company"); }
}
else if($this->Xin_model->get_all_locations()==0)
{
    if($actual_link!=$re_url."location") { header("location:".$re_url."location"); }
}
else if($this->Xin_model->get_all_departments()==0)
{
    if($actual_link!=$re_url."department") { header("location:".$re_url."department"); }
}
else if($this->Xin_model->get_all_designations()==0)
{
    if($actual_link!=$re_url."designation") { header("location:".$re_url."designation"); }
}

$root_account    = $this->Xin_model->get_root_account();
$package_info    = $this->Xin_model->read_package_information($root_account[0]->package_id);
?>

<div class="template-options">
  <div class="to-toggle"><i class="ti-settings"></i></div>
  <div class="custom-scroll custom-scroll-dark">
  <ul class="nav nav-tabs nav-tabs-2 m-b-0-5" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#layout" role="tab" aria-expanded="true">Layout Setting</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab_setting" role="tab" aria-expanded="false">Other Setting</a>
        </li>
    </ul>
    <div class="tab-content">
    <div class="tab-pane active" id="layout" role="tabpanel" aria-expanded="true">            
    <form id="layout_skin_info" action="<?php echo site_url("settings/layout_skin_info");?>/" name="layout_skin_info" method="post">
      <input type="hidden" name="token" value="1DR59ik46kLKR4E" />
      <div class="to-content">
      <?php if($system[0]->enable_layout=='yes'):?>
        <h5><strong><?php echo $this->lang->line('header_layouts');?></strong></h5>
        <div class="row text-xs-center">
          <div class="col-xs-6 mb-2">
            <div class="to-item">
              <label>
              <input name="fixed-header" type="checkbox" value="fixed-header" <?php if($system[0]->fixed_header!=''){?> checked="checked" <?php } ?>>
              <div class="to-icon"><i class="ti-check"></i></div>
              <img src="<?php echo base_url();?>skin/img/layouts/fixed-header.png" class="img-fluid">
              </label>
              <div class="text-muted"><?php echo $this->lang->line('header_fixed_header');?></div>
            </div>
          </div>
          <div class="col-xs-6 mb-2">
            <div class="to-item">
              <label>
              <input name="fixed-sidebar" type="checkbox" value="fixed-sidebar" <?php if($system[0]->fixed_sidebar!=''){?> checked="checked" <?php } ?>>
              <div class="to-icon"><i class="ti-check"></i></div>
              <img src="<?php echo base_url();?>skin/img/layouts/sticky-sidebar.png" class="img-fluid">
              </label>
              <div class="text-muted"><?php echo $this->lang->line('header_fixed_sidebar');?></div>
            </div>
          </div>
          <div class="col-xs-6 mb-2">
            <div class="to-item">
              <label>
              <input name="boxed-wrapper" type="checkbox" value="boxed-wrapper" <?php if($system[0]->boxed_wrapper!=''){?> checked="checked" <?php } ?>>
              <div class="to-icon"><i class="ti-check"></i></div>
              <img src="<?php echo base_url();?>skin/img/layouts/boxed-wrapper.png" class="img-fluid">
              </label>
              <div class="text-muted"><?php echo $this->lang->line('header_boxed_wrapper');?></div>
            </div>
          </div>
          <div class="col-xs-6 mb-2">
            <div class="to-item">
              <label>
              <input name="static" type="checkbox" value="static" <?php if($system[0]->layout_static!=''){?> checked="checked" <?php } ?>>
              <div class="to-icon"><i class="ti-check"></i></div>
              <img src="<?php echo base_url();?>skin/img/layouts/static.png" class="img-fluid">
              </label>
              <div class="text-muted"><?php echo $this->lang->line('header_static');?></div>
            </div>
          </div>
        </div>
        <?php endif;?>
        <h5><strong><?php echo $this->lang->line('header_skins');?></strong></h5>
        <div class="row">
          <div class="col-xs-3 mb-2">
            <label class="skin-label">
            <input name="skin" value="skin-default" type="radio" <?php if($system[0]->system_skin=='skin-default'){?> checked="checked" <?php } ?>>
            <div class="to-icon"><i class="ti-check"></i></div>
            <div class="to-skin"> <span class="skin-dark-blue"></span> <span class="skin-white"></span> <span class="skin-dark-blue"></span> </div>
            </label>
          </div>
          <div class="col-xs-3 mb-2">
            <label class="skin-label">
            <input name="skin" value="skin-1" type="radio" <?php if($system[0]->system_skin=='skin-1'){?> checked="checked" <?php } ?>>
            <div class="to-icon"><i class="ti-check"></i></div>
            <div class="to-skin"> <span class="skin-dark-blue-2"></span> <span class="skin-dark-blue-2"></span> <span class="bg-white"></span> </div>
            </label>
          </div>
          <div class="col-xs-3 mb-2">
            <label class="skin-label">
            <input name="skin" value="skin-2" type="radio" <?php if($system[0]->system_skin=='skin-2'){?> checked="checked" <?php } ?>>
            <div class="to-icon"><i class="ti-check"></i></div>
            <div class="to-skin"> <span class="bg-danger"></span> <span class="bg-white"></span> <span class="bg-black"></span> </div>
            </label>
          </div>
          <div class="col-xs-3 mb-2">
            <label class="skin-label">
            <input name="skin" value="skin-3" type="radio" <?php if($system[0]->system_skin=='skin-3'){?> checked="checked" <?php } ?>>
            <div class="to-icon"><i class="ti-check"></i></div>
            <div class="to-skin"> <span class="bg-white"></span> <span class="bg-white"></span> <span class="bg-white"></span> </div>
            </label>
          </div>
          <div class="col-xs-3 mb-2">
            <label class="skin-label">
            <input name="skin" value="skin-4" type="radio" <?php if($system[0]->system_skin=='skin-4'){?> checked="checked" <?php } ?>>
            <div class="to-icon"><i class="ti-check"></i></div>
            <div class="to-skin"> <span class="bg-white"></span> <span class="skin-dark-blue-2"></span> <span class="bg-white"></span> </div>
            </label>
          </div>
          <div class="col-xs-3 mb-2">
            <label class="skin-label">
            <input name="skin" value="skin-5" type="radio" <?php if($system[0]->system_skin=='skin-5'){?> checked="checked" <?php } ?>>
            <div class="to-icon"><i class="ti-check"></i></div>
            <div class="to-skin"> <span class="bg-primary-light"></span> <span class="bg-primary"></span> <span class="bg-white"></span> </div>
            </label>
          </div>
          <div class="col-xs-3 mb-2">
            <label class="skin-label">
            <input name="skin" value="skin-6" type="radio" <?php if($system[0]->system_skin=='skin-6'){?> checked="checked" <?php } ?>>
            <div class="to-icon"><i class="ti-check"></i></div>
            <div class="to-skin"> <span class="bg-black"></span> <span class="bg-info"></span> <span class="bg-black"></span> </div>
            </label>
          </div>
        </div>
        <div class="to-material">
          <button type="submit" class="btn-block waves-effect waves-light mb-2 btn btn-primary save"><?php echo $this->lang->line('xin_update');?></button>
        </div>
      </div>
    </form></div>
    
    <div class="tab-pane" id="tab_setting" role="tabpanel">
    <form id="sidebar_setting_info" action="<?php echo site_url("settings/sidebar_setting_info");?>/" name="sidebar_setting_info" method="post">
      <input type="hidden" name="token" value="1DR59ik46kLKR4E" />
        <div class="sidebar-settings animated fadeIn">
            <div class="sidebar-group">
                <h6>Main</h6>
                <div class="ss-item">
                    <div class="text-truncate">Enable attendance system</div>
                    <div class="ss-checkbox"><input type="checkbox" id="enable_attendance" class="js-switch" data-size="small" data-color="#3e70c9" <?php if($system[0]->enable_attendance=='yes'):?> checked="checked" <?php endif;?> value="yes"></div>
                </div>
                <div class="ss-item">
                    <div class="text-truncate">Enable jobs for employees</div>
                    <div class="ss-checkbox"><input type="checkbox" id="enable_job" class="js-switch" data-size="small" data-color="#3e70c9" <?php if($system[0]->enable_job_application_candidates=='yes'):?> checked="checked" <?php endif;?> value="yes"></div>
                </div>
                <div class="ss-item">
                    <div class="text-truncate">Employee can change profile background image</div>
                    <div class="ss-checkbox"><input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" <?php if($system[0]->enable_profile_background=='yes'):?> checked="checked" <?php endif;?> value="yes" id="enable_profile_background"></div>
                </div>
            </div>
            <div class="sidebar-group">
                <h6>Notificatiоns</h6>
                <div class="ss-item">
                    <div class="text-truncate">Enable email notifications</div>
                    <div class="ss-checkbox"><input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" <?php if($system[0]->enable_email_notification=='yes'):?> checked="checked" <?php endif;?> value="yes" id="role_email_notification"></div>
                </div>
                <div class="ss-item">
                    <div class="text-truncate">Enable notification close button</div>
                    <div class="ss-checkbox"><input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" <?php if($system[0]->notification_close_btn=='true'):?> checked="checked" <?php endif;?> value="true" id="close_btn"></div>
                </div>
                <div class="ss-item">
                    <div class="text-truncate">Notification progress Bar</div>
                    <div class="ss-checkbox"><input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" <?php if($system[0]->notification_bar=='true'):?> checked="checked" <?php endif;?> value="true" id="notification_bar"></div>
                </div>
            </div>
            <div class="sidebar-group">
                <h6>Other</h6>
                <div class="ss-item">
                    <div class="text-truncate">Enable policy link on top</div>
                    <div class="ss-checkbox"><input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" <?php if($system[0]->enable_policy_link=='yes'):?> checked="checked" <?php endif;?> value="yes" id="role_policy_link"></div>
                </div>
                <div class="ss-item">
                    <div class="text-truncate">Enable layout setting</div>
                    <div class="ss-checkbox"><input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" <?php if($system[0]->enable_layout=='yes'):?> checked="checked" <?php endif;?> value="yes" id="enable_layout"></div>
                </div>
            </div>
        </div>
        <div class="to-material">
          <button type="submit" class="btn-block waves-effect waves-light mb-2 btn btn-primary save"><?php echo $this->lang->line('xin_update');?></button>
        </div>
        </form>
					</div>
                    
                    </div>
  </div>
</div>
<?php }?>

<div class="site-header">

    <!-- Warning Messages -->
    <?php
    
    if($user_info[0]->user_role_id==1) {
    
    $company_logo_count = 0;
    $company_logo_names = '';
    $companies = $this->Xin_model->get_companies();
    foreach($companies as $company)
    {
        if($company->logo=='no file' || $company->logo=='')
        {
            $company_logo_count = 1;
            if(!empty($company_logo_names))
            {
              $company_logo_names.= ', '.$company->name;
            }
            else
            {
              $company_logo_names = $company->name;
            }
        }
    }
    
    $cinfo = $this->Xin_model->read_company_setting_info(1);
    
    if(empty($cinfo[0]->logo))
    {
    ?>
        <div align="center" class="alert alert-danger" style="margin-bottom:0px;">
        <strong>Please update your logo.</strong> It is very important while sending notification mails and others. <a href="<?php echo site_url('settings'); ?>" class="text-info">Click here</a>
        </div>
    <?php
    }
    else if($company_logo_count==1)
    {
    ?>
        <div align="center" class="alert alert-danger" style="margin-bottom:0px;">
        <strong>Please update your company logo (<?php echo $company_logo_names; ?>).</strong> It is very important while creating a payslip. <a href="<?php echo site_url('company'); ?>" class="text-info">Click here</a>
        </div>
    <?php
    }
    
    }
    ?>
    
    <!-- End Warning Messages -->

  <nav class="navbar navbar-<?php echo $cl_skin;?>">
    <div class="navbar-left"> <a class="navbar-brand" href="<?php echo site_url();?>dashboard/">
      <div class="logo"></div>
      </a>
      <div class="toggle-button <?php echo $cl_skin;?> sidebar-toggle-first float-xs-left hidden-md-up" data-toggle-tooltip="tooltip" data-placement="bottom" data-title="Sidebar" data-original-title="" title=""> <span class="hamburger"></span> </div>
      <div class="toggle-button <?php echo $cl_skin;?> float-xs-right hidden-md-up" data-toggle="collapse" data-target="#collapse-1" data-toggle-tooltip="tooltip" data-placement="bottom" data-title="Sidebar" data-original-title="" title=""> <span class="more"></span> </div>
    </div>
    <div class="navbar-right navbar-toggleable-sm collapse" id="collapse-1">
      <div class="toggle-button <?php echo $cl_skin;?> sidebar-toggle-second float-xs-left hidden-sm-down" data-toggle-tooltip="tooltip" data-placement="bottom" data-title="Sidebar" data-original-title="" title=""> <span class="hamburger"></span> </div>
      <ul class="nav navbar-nav float-md-right">
        <?php if($user_info[0]->user_role_id=='1x'){?>
            
            <li class="nav-item"> 
                <a href="<?php echo $accounts_url.'package';?>" class="btn btn-success" style="line-height: 28px; margin:15px;">Subscription</a>
            </li>
            
        <?php
        }
        ?>
        
        <li class="nav-item dropdown hidden-sm-down g-dropdown"> 
            <a href="#" data-toggle="dropdown" aria-expanded="false">
                <span class="avatar box-32">
                    <?php  if($user_info[0]->profile_picture!='' && $user_info[0]->profile_picture!='no file') {?>
                    <img src="<?php  echo $accounts_url.'uploads/profile/'.$user_info[0]->profile_picture;?>" alt="" id="user_avatar" 
                    class="b-a-radius-circle user_profile_avatar">
                    <?php } else {?>
                    <?php  if($user_info[0]->gender=='Male') { ?>
                    <?php 	$de_file = $accounts_url.'uploads/profile/male_avatar.jpg';?>
                    <?php } else { ?>
                    <?php 	$de_file = $accounts_url.'uploads/profile/dmale_avatar.jpg';?>
                    <?php } ?>
                    <img src="<?php  echo $de_file;?>" alt="" id="user_avatar" class=" b-a-radius-circle user_profile_avatar">
                    <?php  } ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right animated <?php echo $system[0]->animation_effect_topmenu;?>" style="min-width:220px;">
                


                <a class="dropdown-item" href="<?php echo $accounts_url;?>profile/">
                    <i class="ti-user mr-0-5"></i> <?php echo $this->lang->line('header_my_profile');?>
                </a>
                <?php if(in_array('53',$role_resources_ids)){?>
                <a class="dropdown-item" href="<?php echo $accounts_url; ?>settings/">
                    <i class="ti-settings mr-0-5"></i> <?php echo $this->lang->line('left_settings');?>
                </a>
                <?php  } ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target=".pro_change_password" data-profile_id="<?php echo $session['user_id'];?>"><i class="fa fa-key mr-0-5"></i> <?php echo $this->lang->line('header_change_password');?></a>
                <a class="dropdown-item" href="<?php echo $accounts_url;?>logout/"><i class="ti-power-off mr-0-5"></i> <?php echo $this->lang->line('header_sign_out');?></a>
            </div>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="nav-item hidden-sm-down"> <a class="nav-link toggle-fullscreen" href="#"> <i class="ti-fullscreen"></i> </a> </li>
       
        <?php if($system[0]->enable_job_application_candidates=='yes'){?>
        <li class="nav-item hidden-sm-down"> <a href="<?php echo site_url();?>frontend/jobs/" target="_blank">
          <button type="button" class="btn btn-outline-success w-min-sm mb-0-25 waves-effect waves-light" style="background:#43b968; color:#fff;"><?php echo $this->lang->line('header_apply_jobs');?></button>
          </a> </li>
        <?php } ?>
        <?php if($user_info[0]->user_role_id!=1) {?>
<!--        --><?php //if($system[0]->enable_attendance == 'yes' && $system[0]->enable_clock_in_btn=='yes'){?>
<!--        <li class="nav-item hidden-sm-down clock-in-btn">-->
<!--          <form name="set_clocking" id="set_clocking_hd" method="post">-->
<!--            <input type="hidden" name="timeshseet" value="--><?php //echo $user_info[0]->user_id;?><!--">-->
<!--            --><?php //$attendances = $this->Xin_model->attendance_time_checks($session['user_id']); $dat = $attendances->result();?>
<!--            --><?php //if($attendances->num_rows() < 1) {?>
<!--            <input type="hidden" value="clock_in" name="clock_state" id="clock_state">-->
<!--            <input type="hidden" value="" name="time_id" id="time_id">-->
<!--            <button class="btn btn-success text-uppercase w-min-sm mb-0-25 waves-effect waves-light" type="submit"><i class="fa fa-arrow-circle-right"></i> --><?php //echo $this->lang->line('dashboard_clock_in');?><!--</button>-->
<!--            --><?php //} else {?>
<!--            <input type="hidden" value="clock_out" name="clock_state" id="clock_state">-->
<!--            <input type="hidden" value="--><?php //echo $dat[0]->time_attendance_id;?><!--" name="time_id" id="time_id">-->
<!--            <button class="btn btn-warning text-uppercase w-min-sm mb-0-25 waves-effect waves-light" type="submit"><i class="fa fa-arrow-circle-left"></i> --><?php //echo $this->lang->line('dashboard_clock_out');?><!--</button>-->
<!--            --><?php //} ?>
<!--          </form>-->
<!--        </li>-->
<!--        --><?php //} ?>
        <?php } ?>
        
      </ul>
    </div>
  </nav>
</div>
<div class="modal fade pro_change_password animated <?php echo $system[0]->animation_effect_modal;?>" id="pro_change_password" tabindex="-1" role="dialog" aria-labelledby="pro_change_password" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="change_password_modal"></div>
  </div>
</div>
<div class="modal fade policy animated <?php echo $system[0]->animation_effect_modal;?>" id="policy" tabindex="-1" role="dialog" aria-labelledby="policy" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="policy_modal"></div>
  </div>
</div>