<?php
/* Settings view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1" >
  <div class="col-md-3">
    <div class="box bg-white">
      <ul class="nav nav-4">
        <li class="nav-item nav-item-link"> <span class="nav-link" href="#setting" data-config="0" data-toggle="tab" aria-expanded="true"> <strong>SYSTEM SETTINGS</strong> </span> </li>
        <li class="nav-item nav-item-link active-link" id="config_1"> <a class="nav-link nav-tabs-link" href="#general" data-config="1" data-config-block="general" data-toggle="tab" aria-expanded="true"> <i class="fa fa-cog"></i> General </a> </li>
        <li class="nav-item nav-item-link" id="config_2"> <a class="nav-link nav-tabs-link" href="#company_logo" data-config="2" data-config-block="company_logo" data-toggle="tab" aria-expanded="true"> <i class="fa fa-camera"></i> <?php echo $this->lang->line('xin_logos');?> </a> </li>
        <li class="nav-item nav-item-link" id="config_3"> <a class="nav-link nav-tabs-link" href="#system" data-config="3" data-config-block="system" data-toggle="tab" aria-expanded="true"> <i class="fa fa-cogs"></i> System </a> </li>
<!--        <li class="nav-item nav-item-link" id="config_4"> <a class="nav-link nav-tabs-link" href="#role" data-config="4" data-config-block="role" data-toggle="tab" aria-expanded="true"> <i class="fa fa-key"></i> Role </a> </li>-->
<!--        <li class="nav-item nav-item-link" id="config_5"> <a class="nav-link nav-tabs-link" href="#attendance" data-config="5" data-config-block="attendance" data-toggle="tab" aria-expanded="true"> <i class="fa fa-book"></i> Attendance </a> </li>-->
<!--        <li class="nav-item nav-item-link" id="config_6"> <a class="nav-link nav-tabs-link" href="#job" data-config="6" data-config-block="job" data-toggle="tab" aria-expanded="true"> <i class="fa fa-bullhorn"></i> Recruitment </a> </li>-->
<!--        <li class="nav-item nav-item-link" id="config_7"> <a class="nav-link nav-tabs-link" href="#email" data-config="7" data-config-block="email" data-toggle="tab" aria-expanded="true"> <i class="ti-email"></i> Email Notifications </a> </li>-->
<!--        <li class="nav-item nav-item-link" id="config_8"> <a class="nav-link nav-tabs-link" href="#animation" data-config="8" data-config-block="animation" data-toggle="tab" aria-expanded="true"> <i class="fa fa-diamond"></i> Animation Effects </a> </li>-->
<!--        <li class="nav-item nav-item-link" id="config_9"> <a class="nav-link nav-tabs-link" href="#notification" data-config="9" data-config-block="notification" data-toggle="tab" aria-expanded="true"> <i class="ti-check"></i> Notification Position </a> </li>-->
<!--        <li class="nav-item nav-item-link" id="config_10"> <a class="nav-link nav-tabs-link" href="#backup" data-config="10" data-config-block="backup" data-toggle="tab" aria-expanded="true"> <i class="ti-cloud-down"></i> Download Backup </a> </li>-->
      </ul>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="general"  aria-expanded="false">
    <form id="company_info" action="<?php echo site_url("settings/company_info").'/'.$company_info_id ?>/" name="company_info" method="post">
      <input type="hidden" name="u_company_info" value="UPDATE">
      <div class="box box-block bg-white">
        <h2><strong>General</strong> Configuration</h2>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="company_name">Company Name</label>
              <input class="form-control" placeholder="Company Name" name="company_name" type="text" value="<?php echo $company_name;?>">
            </div>
            <div class="form-group">
              <label for="contact_person">Contact Person</label>
              <input class="form-control" placeholder="Contact Person" name="contact_person" type="text" value="<?php echo $contact_person;?>">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input class="form-control" placeholder="Email" name="email" type="email" value="<?php echo $email;?>">
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input class="form-control" placeholder="Phone" name="phone" type="text" value="<?php echo $phone;?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="address">Address</label>
              <input class="form-control" placeholder="Address Line 1" name="address_1" type="text" value="<?php echo $address_1;?>">
              <br>
              <input class="form-control" placeholder="Address Line 2" name="address_2" type="text" value="<?php echo $address_2;?>">
              <br>
              <div class="row">
                <div class="col-xs-5">
                  <input class="form-control" placeholder="City" name="city" type="text" value="<?php echo $city;?>">
                </div>
                <div class="col-xs-4">
                  <input class="form-control" placeholder="State" name="state" type="text" value="<?php echo $state;?>">
                </div>
                <div class="col-xs-3">
                  <input class="form-control" placeholder="Zipcode" name="zipcode" type="text" value="<?php echo $zipcode;?>">
                </div>
              </div>
              <br>
              <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="Country">
                <option value="">Select One</option>
                <?php foreach($all_countries as $scountry) {?>
                <option value="<?php echo $scountry->country_id;?>" <?php if($country==$scountry->country_id):?> selected <?php endif;?>> <?php echo $scountry->country_name;?></option>
                <?php } ?>
              </select>
            </div>
            <input name="config_type" type="hidden" value="general">
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  
  
  
  
  
  
  
  <div class="col-md-9 current-tab animated fadeInRight" id="company_logo" style="display:none;">
    <div class="box box-block bg-white">
      <form id="logo_info" action="<?php echo site_url("settings/logo_info").'/'.$company_info_id ?>/" name="logo_info" method="post">
        <input type="hidden" name="company_logo" value="UPDATE">
        <h2><strong>Main Company Logo</strong></h2>
        <div class="row">
          <div class="col-md-12">
            <div class='form-group'>
              <input type="file" name="p_file" id="p_file">
              <?php if($logo!='' && $logo!='no file') {?>
              <img src="<?php echo base_url().'uploads/logo/'.$logo;?>" width="170px" style="margin-left:30px;" id="u_file">
              <?php } else {?>
              <img src="<?php echo base_url().'uploads/logo/no_logo.png';?>" width="170px" style="margin-left:30px;" id="u_file">
              <?php } ?>
              <br>
              <small>- <?php echo $this->lang->line('xin_logo_files_only');?></small><br />
              <small>- <?php echo $this->lang->line('xin_best_logo_size');?></small><br />
              <small>- <?php echo $this->lang->line('xin_logo_whit_background_black_text');?></small> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="text-right">
              <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
            </div>
          </div>
        </div>
      </form>
    </div>
    
    <!--
    <div class="box box-block bg-white">
      <form id="payroll_logo" name="payroll_logo" method="post" enctype="multipart/form-data">
        <input type="hidden" name="payroll_logo" value="UPDATE">
        <h2><strong>Payroll</strong> Logo <small>(for pdf)</small></h2>
        <div class="row">
          <div class="col-md-12">
            <div class='form-group'>
              <input type="file" name="p_file5" id="p_file5">
              <?php if($payroll_logo!='' && $payroll_logo!='no file') {?>
              <img src="<?php echo base_url().'uploads/logo/payroll/'.$payroll_logo;?>" width="170px" style="margin-left:30px;" id="u_file5">
              <?php } else {?>
              <img src="<?php echo base_url().'uploads/logo/no_logo.png';?>" width="170px" style="margin-left:30px;" id="u_file5">
              <?php } ?>
              <br>
              <small>- Upload files only: gif,png,jpg,jpeg</small><br />
              <small>- Best Size: 160x40</small><br />
              <small>- White background with black text</small> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="text-right">
              <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
            </div>
          </div>
        </div>
      </form>
    </div>
    -->
  </div>
  
  
  
  

  <div class="col-md-9 current-tab animated fadeInRight" id="system" style="display:none;">
    <form id="system_info" action="<?php echo site_url("settings/system_info").'/'.$company_info_id ?>/" name="system_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
      <div class="box box-block bg-white">
        <h2><strong>System</strong> Configuration</h2>
        <div class="col-sm-6">
          <div class="form-group">
            <input class="form-control" placeholder="Application Name" name="application_name" type="hidden" value="<?php echo $application_name;?>" id="application_name">
          </div>
          <div class="form-group">
            <label for="email">Default Currency</label>
            <select class="form-control select2-hidden-accessible" name="default_currency_symbol" data-plugin="select_hrm" data-placeholder="Default Currency Symbol" tabindex="-1" aria-hidden="true">
              <option value="">Select One</option>
              <?php foreach($this->Xin_model->get_currencies() as $currency){?>
              <?php $_currency = $currency->code.' - '.$currency->symbol;?>
              <option value="<?php echo $_currency;?>" <?php if($default_currency_symbol==$_currency):?> selected <?php endif;?>> <?php echo $_currency;?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="phone">Default Currency (Symbol/Code)</label>
            <select class="form-control" name="show_currency" data-plugin="select_hrm" data-placeholder="Show Currency">
              <option value="">Select One</option>
              <option value="code" <?php if($show_currency=='code'){?> selected <?php }?>>Currency Code</option>
              <option value="symbol" <?php if($show_currency=='symbol'){?> selected <?php }?>>Currency Symbol</option>
            </select>
          </div>
          <div class="form-group">
            <label for="phone">Currency Position</label>
            <input type="hidden" name="notification_position" value="Bottom Left">
            <input type="hidden" name="enable_registration" value="no">
            <input type="hidden" name="login_with" value="username">
            <select class="form-control" name="currency_position" data-plugin="select_hrm" data-placeholder="Currency Position">
              <option value="">Select One</option>
              <option value="Prefix" <?php if($currency_position=='Prefix'){?> selected <?php }?>>Prefix</option>
              <option value="Suffix" <?php if($currency_position=='Suffix'){?> selected <?php }?>>Suffix</option>
            </select>
          </div>
          
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="company_name">Date Format</label>
            <br>
            <label class="custom-control custom-radio">
              <input id="date_format" name="date_format" type="radio" class="custom-control-input" value="d-m-Y" <?php if($date_format_xi=='d-m-Y'){?> checked <?php }?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">dd-mm-YYYY (<?php echo date('d-m-Y');?>)</span> </label>
            <br>
            <label class="custom-control custom-radio">
              <input id="date_format" name="date_format" type="radio" class="custom-control-input" value="m-d-Y" <?php if($date_format_xi=='m-d-Y'){?> checked <?php }?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">mm-dd-YYYY (<?php echo date('m-d-Y');?>)</span> </label>
            <br>
            <label class="custom-control custom-radio">
              <input id="date_format" name="date_format" type="radio" class="custom-control-input" value="d-M-Y" <?php if($date_format_xi=='d-M-Y'){?> checked <?php }?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">dd-MM-YYYY (<?php echo date('d-M-Y');?>)</span> </label>
            <br>
            <label class="custom-control custom-radio">
              <input id="date_format" name="date_format" type="radio" class="custom-control-input" value="M-d-Y" <?php if($date_format_xi=='M-d-Y'){?> checked <?php }?>>
              <span class="custom-control-indicator"></span> <span class="custom-control-description">MM-dd-YYYY (<?php echo date('M-d-Y');?>)</span> </label>
          </div>
          
          
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="role" style="display:none;">
    <form id="role_info" action="<?php echo site_url("settings/role_info").'/'.$company_info_id ?>/" name="role_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
      <div class="box box-block bg-white">
        <h2><strong>Role</strong> Configuration</h2>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="contact_role">Employee can manage own contact information</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="contact_role" <?php if($employee_manage_own_contact=='yes'):?> checked="checked" <?php endif;?> value="yes" />
            </div>
          </div>
          <div class="form-group">
            <label for="bank_account_role">Employee can manage own bank account</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="bank_account_role" <?php if($employee_manage_own_bank_account=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="edu_role">Employee can manage own qualification</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="edu_role" <?php if($employee_manage_own_qualification=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="work_role">Employee can manage own work experience</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="work_role" <?php if($employee_manage_own_work_experience=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="doc_role">Employee can manage own documents</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="doc_role" <?php if($employee_manage_own_document=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="pic_role">Employee can manage own profile picture</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="pic_role" <?php if($employee_manage_own_picture=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="profile_role">Employee can manage own profile information</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="profile_role" <?php if($employee_manage_own_profile=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="social_role">Employee can manage own social information</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="social_role" <?php if($employee_manage_own_social=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="attendance" style="display:none;">
    <form id="attendance_info" action="<?php echo site_url("settings/attendance_info").'/'.$company_info_id ?>/" name="attendance_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
      <div class="box box-block bg-white">
        <h2>Attendance Configuration</h2>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="enable_attendance">Enable clock-in button on header (<small>It will show everywhere on the system</small>)</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="enable_clock_in_btn" <?php if($enable_clock_in_btn=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="enable_clock_in_btn">Enable clock in & clock out</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="enable_attendances" <?php if($enable_attendance=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  
  <div class="col-md-9 current-tab animated fadeInRight" id="job" style="display:none;">
    <div class="box box-block bg-white">
      <form id="job_info" action="<?php echo site_url("settings/job_info").'/'.$company_info_id ?>/" name="job_info" method="post">
        <input type="hidden" name="user_id" value="<?php //echo $row['user_id'];?>">
        <input type="hidden" name="u_basic_info" value="UPDATE">
        <h2><strong>Recruitment</strong> Configuration</h2>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="enable_job">Enable Jobs for employees</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="enable_job2" <?php if($enable_job_application_candidates=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
          <div class="form-group">
            <label for="job_application_format">Job Application file format</label>
            <br>
            <input type="text" value="<?php echo $job_application_format;?>" data-role="tagsinput" name="job_application_format">
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="email" style="display:none;">
    <form id="email_info" action="<?php echo site_url("settings/email_info").'/'.$company_info_id ?>/" name="email_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
      <div class="box box-block bg-white">
        <h2>Email Notifications Configuration</h2>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="company_name">Enable email notifications</label>
            <br>
            <div class="pull-xs-left m-r-1">
              <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="srole_email_notification" <?php if($enable_email_notification=='yes'):?> checked="checked" <?php endif;?> value="yes">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="animation"  aria-expanded="false" style="display:none;">
    <div class="box box-block bg-white">
      <h2><strong>Animation Effects</strong> Configuration</h2>
      <form id="animation_effect_info" action="<?php echo site_url("settings/animation_effect_info");?>" name="animation_effect_info" method="post">
        <input type="hidden" name="u_basic_info" value="UPDATE">
        <div class="row">
          <div class="col-md-12">
            <div class="col-sm-6">
              <input name="employee_manage_own_bank_account" type="hidden" value="yes">
              <div class="form-group">
                <label for="animation_effect_topmenu">Animation Effect</label>
                <br>
                <select class="form-control" name="animation_effect_topmenu" data-plugin="select_hrm" data-placeholder="Animation Effect">
                  <option value="">Select One</option>
                  <option value="fadeInDown" <?php if($animation_effect_topmenu=='fadeInDown'){?> selected <?php }?>>fadeInDown</option>
                  <option value="fadeInUp" <?php if($animation_effect_topmenu=='fadeInUp'){?> selected <?php }?>>fadeInUp</option>
                  <option value="fadeInLeft" <?php if($animation_effect_topmenu=='fadeInLeft'){?> selected <?php }?>>fadeInLeft</option>
                  <option value="fadeInRight" <?php if($animation_effect_topmenu=='fadeInRight'){?> selected <?php }?>>fadeInRight</option>
                  <option value="fadeIn" <?php if($animation_effect_topmenu=='fadeIn'){?> selected <?php }?>>fadeIn</option>
                  <option value="growIn" <?php if($animation_effect_topmenu=='growIn'){?> selected <?php }?>>growIn</option>
                  <option value="rotateIn" <?php if($animation_effect_topmenu=='rotateIn'){?> selected <?php }?>>rotateIn</option>
                  <option value="rotateInUpLeft" <?php if($animation_effect_topmenu=='rotateInUpLeft'){?> selected <?php }?>>rotateInUpLeft</option>
                  <option value="rotateInDownLeft" <?php if($animation_effect_topmenu=='rotateInDownLeft'){?> selected <?php }?>>rotateInDownLeft</option>
                  <option value="rotateInUpRight" <?php if($animation_effect_topmenu=='rotateInUpRight'){?> selected <?php }?>>rotateInUpRight</option>
                  <option value="rotateInDownRight" <?php if($animation_effect_topmenu=='rotateInDownRight'){?> selected <?php }?>>rotateInDownRight</option>
                  <option value="rollIn" <?php if($animation_effect_topmenu=='rollIn'){?> selected <?php }?>>rollIn</option>
                  <option value="swing" <?php if($animation_effect_topmenu=='swing'){?> selected <?php }?>>swing</option>
                  <option value="tada" <?php if($animation_effect_topmenu=='tada'){?> selected <?php }?>>tada</option>
                  <option value="pulse" <?php if($animation_effect_topmenu=='pulse'){?> selected <?php }?>>pulse</option>
                  <option value="flipInX" <?php if($animation_effect_topmenu=='flipInX'){?> selected <?php }?>>flipInX</option>
                  <option value="flipInY" <?php if($animation_effect_topmenu=='flipInY'){?> selected <?php }?>>flipInY</option>
                </select>
                <br />
                <p class="text-muted"><i class="fa fa-arrow-up"></i> Set animation effect for top menu.</p>
                <input type="hidden" name="animation_effect" id="animation_effect" value="fadeInDown" />
              </div>
            </div>
            <div class="col-sm-6">
              <input name="employee_manage_own_bank_account" type="hidden" value="yes">
              <div class="form-group">
                <label for="animation_effect_modal">Animation Effect</label>
                <br>
                <select class="form-control" name="animation_effect_modal" data-plugin="select_hrm" data-placeholder="Animation Effect">
                  <option value="">Select One</option>
                  <option value="fadeInDown" <?php if($animation_effect_modal=='fadeInDown'){?> selected <?php }?>>fadeInDown</option>
                  <option value="fadeInUp" <?php if($animation_effect_modal=='fadeInUp'){?> selected <?php }?>>fadeInUp</option>
                  <option value="fadeInLeft" <?php if($animation_effect_modal=='fadeInLeft'){?> selected <?php }?>>fadeInLeft</option>
                  <option value="fadeInRight" <?php if($animation_effect_modal=='fadeInRight'){?> selected <?php }?>>fadeInRight</option>
                  <option value="fadeIn" <?php if($animation_effect_modal=='fadeIn'){?> selected <?php }?>>fadeIn</option>
                  <option value="growIn" <?php if($animation_effect_modal=='growIn'){?> selected <?php }?>>growIn</option>
                  <option value="rotateIn" <?php if($animation_effect_modal=='rotateIn'){?> selected <?php }?>>rotateIn</option>
                  <option value="rotateInUpLeft" <?php if($animation_effect_modal=='rotateInUpLeft'){?> selected <?php }?>>rotateInUpLeft</option>
                  <option value="rotateInDownLeft" <?php if($animation_effect_modal=='rotateInDownLeft'){?> selected <?php }?>>rotateInDownLeft</option>
                  <option value="rotateInUpRight" <?php if($animation_effect_modal=='rotateInUpRight'){?> selected <?php }?>>rotateInUpRight</option>
                  <option value="rotateInDownRight" <?php if($animation_effect_modal=='rotateInDownRight'){?> selected <?php }?>>rotateInDownRight</option>
                  <option value="rollIn" <?php if($animation_effect_modal=='rollIn'){?> selected <?php }?>>rollIn</option>
                  <option value="swing" <?php if($animation_effect_modal=='swing'){?> selected <?php }?>>swing</option>
                  <option value="tada" <?php if($animation_effect_modal=='tada'){?> selected <?php }?>>tada</option>
                  <option value="pulse" <?php if($animation_effect_modal=='pulse'){?> selected <?php }?>>pulse</option>
                  <option value="flipInX" <?php if($animation_effect_modal=='flipInX'){?> selected <?php }?>>flipInX</option>
                  <option value="flipInY" <?php if($animation_effect_modal=='flipInY'){?> selected <?php }?>>flipInY</option>
                </select>
                <br />
                <p class="text-muted"><i class="fa fa-arrow-up"></i> Set animation effect for modal dialogs.</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="text-right">
                    <button type="submit" class="btn btn-primary save col-right"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="notification" style="display:none;">
    <form id="notification_position_info" action="<?php echo site_url("settings/notification_position_info");?>" name="notification_position_info" method="post">
      <input type="hidden" name="u_basic_info" value="UPDATE">
      <div class="box box-block bg-white">
        <h2><strong>Notification Position</strong> Configuration</h2>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="notification_position">Position</label>
              <select class="form-control" name="notification_position" data-plugin="select_hrm" data-placeholder="Position">
                <option value="">Select One</option>
                <option value="toast-top-right" <?php if($notification_position=='toast-top-right'){?> selected <?php }?>>Top Right</option>
                <option value="toast-bottom-right" <?php if($notification_position=='toast-bottom-right'){?> selected <?php }?>>Bottom Right</option>
                <option value="toast-bottom-left" <?php if($notification_position=='toast-bottom-left'){?> selected <?php }?>>Bottom Left</option>
                <option value="toast-top-left" <?php if($notification_position=='toast-top-left'){?> selected <?php }?>>Top Left</option>
                <option value="toast-top-center" <?php if($notification_position=='toast-top-center'){?> selected <?php }?>>Top Center</option>
              </select>
              <br />
              <small class="text-muted"><i class="icon-arrow-up8"></i> Set position for notifications .</small> </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="company_name">Enable Close Button</label>
              <br>
              <div class="pull-xs-left m-r-1">
                <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="sclose_btn" <?php if($notification_close_btn=='true'):?> checked="checked" <?php endif;?> value="true" />
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="company_name">Progress Bar</label>
              <br>
              <div class="pull-xs-left m-r-1">
                <input type="checkbox" class="js-switch" data-size="small" data-color="#3e70c9" data-secondary-color="#ddd" id="snotification_bar" <?php if($notification_bar=='true'):?> checked="checked" <?php endif;?> value="true" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  
  
  
  
  <div class="col-md-9 current-tab animated fadeInRight" id="backup" style="display:none;">
      <div class="box box-block bg-white">
        <h2><strong>Download</strong> Backup</h2>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
                 <button class="btn btn-primary" aria-label="Left Align" onclick="location.href='<?php echo site_url("settings/download_backup");?>';">
                 <span class="fa fa-cloud-download fa-lg" aria-hidden="true"></span> Download
                 </button>
              <br />
              </div>
          </div>
        </div>
      </div>
  </div>
  
  
</div>
