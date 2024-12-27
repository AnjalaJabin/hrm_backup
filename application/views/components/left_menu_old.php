<?php
$session = $this->session->userdata('username');
$user_info = $this->Xin_model->read_user_info($session['user_id']);
$role_user = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
$designation_info = $this->Xin_model->read_designation_info($user_info[0]->designation_id);
$role_resources_ids = explode(',',$role_user[0]->role_resources);

$subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
$subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
$subdomain_name; // Print the sub domain

$accounts_url = base_url();
//'https://cbdemo.g4demo.com/hrm/';

?>
<!-- menu start-->

<div class="site-sidebar">
    <div class="custom-scroll custom-scroll-light">
        <ul class="sidebar-menu">
            <?php
            // user role menu
            //// if(in_array($_menu['md'],$role_resources_ids)) {
            ?>
            <li class="menu-title"><?php echo $this->lang->line('dashboard_main');?></li>
            <li> <a href="<?php echo site_url('dashboard');?>" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-home"></i></span> <span class="s-text"><?php echo $this->lang->line('dashboard_title');?></span> </a> </li>

            <?php  if(in_array('11',$role_resources_ids) || in_array('13',$role_resources_ids) || in_array('14',$role_resources_ids) || in_array('15',$role_resources_ids) || in_array('16',$role_resources_ids) || in_array('17',$role_resources_ids) || in_array('18',$role_resources_ids) || in_array('19',$role_resources_ids) || in_array('20',$role_resources_ids) || in_array('21',$role_resources_ids) || in_array('22',$role_resources_ids) || in_array('23',$role_resources_ids) || in_array('24',$role_resources_ids) || in_array('25',$role_resources_ids) || in_array('26',$role_resources_ids) || in_array('27',$role_resources_ids)){?>
                <li class="with-sub"> <a href="javascript:void(0);" class="waves-effect  waves-light"> <span class="s-caret"><i class="fa fa-angle-down"></i></span> <span class="s-icon"><i class="fa fa-book"></i></span> <span class="s-text">on Boarding</span> </a>
                    <ul>
                        <?php  if(in_array('13',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>employee/job_applied">All Application</a></li>
                        <?php } ?>
                        <?php  if(in_array('14',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>employee/job_interviews">Job Interviews</a></li>
                        <?php } ?>
                        <?php  if(in_array('15',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>job_candidates">Job Candidates</a></li>
                        <?php } ?>
                        <?php  if(in_array('16',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>job_post">job Post</a></li>
                        <?php } ?>
                        <?php  if(in_array('18',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>frontend/jobs">Job apply</a></li>
                        <?php } ?>
                        <!--<?php  if(in_array('20',$role_resources_ids)) { ?>-->
                        <!--<li><a href="<?php echo site_url();?>promotion"><?php echo $this->lang->line('left_promotions');?></a></li>-->
                        <!--<?php } ?>-->
                        <!--<?php if(in_array('21',$role_resources_ids)) { ?>-->
                        <!--<li><a href="<?php echo site_url();?>complaints"><?php echo $this->lang->line('left_complaints');?></a></li>-->
                        <!--<?php } ?>-->
                        <!--<?php if(in_array('22',$role_resources_ids)) { ?>-->
                        <!--<li><a href="<?php echo site_url();?>warning"><?php echo $this->lang->line('left_warnings');?></a></li>-->
                        <!--<?php } ?>-->
                        <!--<?php  if(in_array('26',$role_resources_ids)) { ?>-->
                        <!--<li><a href="<?php echo site_url();?>employees_last_login"><?php echo $this->lang->line('left_employees_last_login');?></a></li>-->
                        <!--<?php } ?>-->

                        <!--<?php if(in_array('27',$role_resources_ids)) { ?>-->
                        <!--<li><a href="<?php echo site_url();?>employee_exit"><?php echo $this->lang->line('left_employees_exit');?></a></li>-->
                        <!--<?php } ?>-->
                        <!--<?php  if(in_array('17',$role_resources_ids)) { ?>-->
                        <!--<li><a href="<?php echo site_url();?>resignation"><?php echo $this->lang->line('left_resignations');?></a></li>-->
                        <!--<?php } ?>-->
                        <!--<?php if(in_array('23',$role_resources_ids)) { ?>-->
                        <!--<li><a href="<?php echo site_url();?>termination"><?php echo $this->lang->line('left_terminations');?></a></li>-->
                    <?php } ?>

                    </ul>
                </li>
            <?php } ?>

            <?php  if(in_array('11',$role_resources_ids) || in_array('13',$role_resources_ids) || in_array('14',$role_resources_ids) || in_array('15',$role_resources_ids) || in_array('16',$role_resources_ids) || in_array('17',$role_resources_ids) || in_array('18',$role_resources_ids) || in_array('19',$role_resources_ids) || in_array('20',$role_resources_ids) || in_array('21',$role_resources_ids) || in_array('22',$role_resources_ids) || in_array('23',$role_resources_ids) || in_array('24',$role_resources_ids) || in_array('25',$role_resources_ids) || in_array('26',$role_resources_ids) || in_array('27',$role_resources_ids)){?>
                <li class="with-sub"> <a href="javascript:void(0);" class="waves-effect  waves-light"> <span class="s-caret"><i class="fa fa-angle-down"></i></span> <span class="s-icon"><i class="ti-user"></i></span> <span class="s-text"><?php echo $this->lang->line('dashboard_employees');?></span> </a>
                    <ul>
                        <?php  if(in_array('13',$role_resources_ids)) { ?>
                            <li><a href="<?php echo $accounts_url;?>employees">All <?php echo $this->lang->line('dashboard_employees');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('14',$role_resources_ids)) { ?>
                            <li><a href="<?php echo $accounts_url;?>roles"><?php echo $this->lang->line('left_set_roles');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('15',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>awards"><?php echo $this->lang->line('left_awards');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('16',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>transfers"><?php echo $this->lang->line('left_transfers');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('18',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>travel"><?php echo $this->lang->line('left_travels');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('20',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>promotion"><?php echo $this->lang->line('left_promotions');?></a></li>
                        <?php } ?>
                        <?php if(in_array('21',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>complaints"><?php echo $this->lang->line('left_complaints');?></a></li>
                        <?php } ?>
                        <?php if(in_array('22',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>warning"><?php echo $this->lang->line('left_warnings');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('26',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>employees_last_login"><?php echo $this->lang->line('left_employees_last_login');?></a></li>
                        <?php } ?>

                        <?php if(in_array('27',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>employee_exit"><?php echo $this->lang->line('left_employees_exit');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('17',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>resignation"><?php echo $this->lang->line('left_resignations');?></a></li>
                        <?php } ?>
                        <?php if(in_array('23',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>termination"><?php echo $this->lang->line('left_terminations');?></a></li>
                        <?php } ?>

                    </ul>
                </li>
            <?php } ?>

            <?php
            $pending_advance_salary = $this->Xin_model->get_all_pending_advance_salary_request();
            $pending_loan = $this->Xin_model->get_all_pending_loan_request();
            $all_request = $pending_advance_salary+$pending_loan;
            if(in_array('36',$role_resources_ids) || in_array('38',$role_resources_ids) || in_array('39',$role_resources_ids) || in_array('40',$role_resources_ids) || in_array('41',$role_resources_ids) || in_array('42',$role_resources_ids)){?>
                <li class="with-sub"> <a href="javascript:void(0);" class="waves-effect  waves-light"> <span class="s-caret"><i class="fa fa-angle-down"></i></span> <span class="s-icon"><i class="fa fa-calculator"></i></span> <span class="s-text"><?php echo $this->lang->line('left_payroll');?>  <?php if($all_request>0){ ?><span style="border-radius: 25px;" class="tag tag-success pull-right"><?php echo $all_request;?> </span> <?php } ?> </span> </a>
                    <ul>
                        <!--
          <?php if(in_array('38',$role_resources_ids)) { ?>
          <li><a href="<?php echo site_url();?>payroll/templates/"><?php echo $this->lang->line('left_payroll_templates');?></a></li>
          <?php } ?>
          <?php if(in_array('39',$role_resources_ids)) { ?>
          <li><a href="<?php echo site_url();?>payroll/hourly_wages/"><?php echo $this->lang->line('left_hourly_wages');?></a></li>
          <?php } ?>
          <?php if(in_array('40',$role_resources_ids)) { ?>
          <li><a href="<?php echo site_url();?>payroll/manage_salary/"><?php echo $this->lang->line('left_manage_salary');?></a></li>
          <?php } ?>
          -->
                        <?php if(in_array('40',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url('payroll/advance_salary');?>">Advance Salary  <?php if($this->Xin_model->get_all_pending_advance_salary_request()>0){ ?><span style="border-radius: 25px;" class="tag tag-success pull-right"><?php echo $this->Xin_model->get_all_pending_advance_salary_request();?> </span> <?php } ?> </a></li>
                        <?php } ?>
                        <?php if(in_array('40',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url('payroll/loan');?>">Loan  <?php if($this->Xin_model->get_all_pending_loan_request()>0){ ?><span style="border-radius: 25px;" class="tag tag-success pull-right"><?php echo $this->Xin_model->get_all_pending_loan_request();?> </span> <?php } ?> </a></li>
                        <?php } ?>
                        <?php if(in_array('41',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>payroll/generate_payslip/"><?php echo $this->lang->line('left_generate_payslip');?></a></li>
                        <?php } ?>
                        <li> <a href="<?php echo site_url('employee/payslip/');?>" class="waves-effect waves-light"><?php echo $this->lang->line('left_payslips');?></a> </li>
                        <li><a href="<?php echo site_url('employee/payroll/advance_salary');?>"><?php echo $this->lang->line('xin_advance_salary');?></a></li>
                        <li><a href="<?php echo site_url('employee/payroll/advance_salary_report');?>"><?php echo $this->lang->line('xin_advance_salary_report');?></a></li>
                    </ul>
                </li>
            <?php } ?>

            <?php  if(in_array('28',$role_resources_ids) || in_array('29',$role_resources_ids) || in_array('30',$role_resources_ids) || in_array('31',$role_resources_ids) || in_array('32',$role_resources_ids) || in_array('33',$role_resources_ids) || in_array('34',$role_resources_ids) || in_array('35',$role_resources_ids)){?>
                <li class="with-sub"> <a href="javascript:void(0);" class="waves-effect  waves-light"> <span class="s-caret"><i class="fa fa-angle-down"></i></span> <span class="s-icon"><i class="fa fa-clock-o"></i></span> <span class="s-text"><?php echo $this->lang->line('left_timesheet');?> <?php if($this->Xin_model->get_all_pending_leaves()>0){ ?><span style="border-radius: 25px;" class="tag tag-success"><?php echo $this->Xin_model->get_all_pending_leaves();?> </span> <?php } ?> </span> </a>
                    <ul>
                        <?php  if(in_array('29',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>timesheet/attendance/"><?php echo $this->lang->line('left_attendance');?></a></li>
                        <?php } ?>
                        <?php if(in_array('30',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>timesheet/date_wise_attendance/"><?php echo $this->lang->line('left_date_wise_attendance');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('31',$role_resources_ids)) { ?>
                            <li><a href="<?php echo base_url();?>timesheet/update_attendance/"><?php echo $this->lang->line('left_update_attendance');?></a></li>
                        <?php } ?>
                        <?php  if(in_array('29',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>timesheet/import/"><?php echo $this->lang->line('left_import_attendance');?></a></li>
                        <?php } ?>
                        <?php if(in_array('32',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>timesheet/leave/"><?php echo $this->lang->line('left_leaves');?>  <?php if($this->Xin_model->get_all_pending_leaves()>0){ ?><span style="border-radius: 25px;" class="tag tag-success pull-right"><?php echo $this->Xin_model->get_all_pending_leaves();?> </span> <?php } ?>  </a></li>
                        <?php } ?>
                        <?php if(in_array('34',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>timesheet/office_shift/"><?php echo $this->lang->line('left_office_shifts');?></a></li>
                        <?php } ?>
                        <?php if(in_array('35',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>timesheet/holidays/"><?php echo $this->lang->line('left_holidays');?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>



            <li class="with-sub"> <a href="javascript:void(0);" class="waves-effect  waves-light"> <span class="s-caret"><i class="fa fa-angle-down"></i></span> <span class="s-icon"><i class="fa fa-book"></i></span> <span class="s-text">Reports </span> </a>

                <ul>
                    <?php
                    if(in_array('61',$role_resources_ids) || in_array('40',$role_resources_ids) || in_array('42',$role_resources_ids)){?>
                        <li><a href="<?php echo site_url('payroll/advance_salary_report');?>">Advance Salary Report</a></li>
                        <li><a href="<?php echo site_url('payroll/loan_report');?>">Loan Report</a></li>
                        <li><a href="<?php echo site_url();?>payroll/payment_history/"><?php echo $this->lang->line('left_payment_history');?></a></li>

                    <?php } ?>


                </ul>
            </li>


            <?php if(in_array('240',$role_resources_ids) || in_array('24',$role_resources_ids) || in_array('25',$role_resources_ids)){?>
                <li class="with-sub"> <a href="javascript:void(0);" class="waves-effect  waves-light"> <span class="s-caret"><i class="fa fa-angle-down"></i></span> <span class="s-icon"><i class="fa fa-dribbble"></i></span> <span class="s-text"><?php echo $this->lang->line('left_performance');?></span> </a>
                    <ul>
                        <?php if(in_array('24',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>performance_indicator"><?php echo $this->lang->line('left_performance_indicator');?></a></li>
                        <?php } ?>
                        <?php if(in_array('25',$role_resources_ids)) { ?>
                            <li><a href="<?php echo site_url();?>performance_appraisal"><?php echo $this->lang->line('left_performance_appraisal');?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <li> <a href="<?php echo site_url();?>policy" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-paperclip"></i></span> <span class="s-text">Policies</span> </a> </li>
            <?php if(in_array('52',$role_resources_ids)){?>
                <li> <a href="<?php echo $accounts_url;?>employees/directory/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-user"></i></span> <span class="s-text"><?php echo $this->lang->line('left_employees_directory');?></span> </a> </li>
            <?php } ?>
            <?php if($role_user[0]->role_id!=1) {?>
                <li> <a href="<?php echo site_url();?>employee/attendance/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-clock-o"></i></span> <span class="s-text"><?php echo $this->lang->line('left_attendance');?></span> </a> </li>
                <li> <a href="<?php echo site_url();?>employee/leave/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-bed"></i></span> <span class="s-text"><?php echo $this->lang->line('left_leave');?></span> </a> </li>
                <li> <a href="<?php echo site_url();?>employee/annual_leave/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-suitcase"></i></span> <span class="s-text">Annual Leave</span> </a> </li>
                <li> <a href="<?php echo site_url();?>flights" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-plane"></i></span> <span class="s-text">Flight Ticket</span> </a> </li>
                <li> <a href="<?php echo site_url();?>gratuity" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-money"></i></span> <span class="s-text">Gratuity Details</span> </a> </li>
                <li> <a href="<?php echo site_url();?>compoff" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-suitcase"></i></span> <span class="s-text">Compensatory Leaves</span> </a> </li>
                <li> <a href="<?php echo site_url();?>frontend/jobs/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-book"></i></span> <span class="s-text">Talent pool</span> </a> </li>

                <li class="with-sub"> <a href="javascript:void(0);" class="waves-effect  waves-light"> <span class="s-caret"><i class="fa fa-angle-down"></i></span> <span class="s-icon"><i class="fa fa-dollar"></i></span> <span class="s-text">Loan</span> </a>
                    <ul>
                        <li><a href="<?php echo site_url('employee/payroll/loan');?>">Loan</a></li>
                        <li><a href="<?php echo site_url('employee/payroll/loan_report');?>">Loan Report</a></li>
                    </ul>
                </li>
                <li class="with-sub"> <a href="javascript:void(0);" class="waves-effect  waves-light"> <span class="s-caret"><i class="fa fa-angle-down"></i></span> <span class="s-icon"><i class="fa fa-list"></i></span> <span class="s-text">Others</span> </a>
                    <ul>
                        <li> <a href="<?php echo site_url();?>employee/announcement/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-sticky-note"></i></span> <span class="s-text"><?php echo $this->lang->line('left_announcements');?></span> </a> </li>
                        <li> <a href="<?php echo site_url();?>leaves/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-sticky-note"></i></span> <span class="s-text">Leaves</span> </a> </li>
                        <li> <a href="<?php echo site_url();?>employee/awards/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-trophy"></i></span> <span class="s-text"><?php echo $this->lang->line('left_awards');?></span> </a> </li>
                        <li> <a href="<?php echo site_url();?>employee/performance/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-edit"></i></span> <span class="s-text"><?php echo $this->lang->line('left_performance');?></span> </a> </li>
                        <li> <a href="<?php echo site_url();?>employee/transfer/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-refresh"></i></span> <span class="s-text"><?php echo $this->lang->line('left_transfers');?></span> </a> </li>
                        <li> <a href="<?php echo site_url();?>employee/promotion/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-star-o"></i></span> <span class="s-text"><?php echo $this->lang->line('left_promotions');?></span> </a> </li>
                        <li> <a href="<?php echo site_url();?>employee/complaints/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-exclamation-circle"></i></span> <span class="s-text"><?php echo $this->lang->line('left_complaints');?></span> </a> </li>
                        <li> <a href="<?php echo site_url();?>employee/warning/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-exclamation-triangle"></i></span> <span class="s-text"><?php echo $this->lang->line('left_warnings');?></span> </a> </li>
                        <li> <a href="<?php echo site_url();?>employee/travels/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-plane"></i></span> <span class="s-text"><?php echo $this->lang->line('left_travels');?></span> </a> </li>
                        <li> <a href="<?php echo site_url();?>employee/office_shift/" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-history"></i></span> <span class="s-text"><?php echo $this->lang->line('left_office_shift');?></span> </a> </li>
                    </ul>
                </li>
            <?php } ?>
            <li> <a href="<?php echo site_url();?>settings" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-cog"></i></span> <span class="s-text">Settings</span> </a> </li>

            <li> <a href="<?php echo site_url();?>settings/constants" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-check"></i></span> <span class="s-text">Set constants</span> </a> </li>
            <li> <a href="<?php echo site_url();?>logout" class="waves-effect waves-light"> <span class="s-icon"><i class="fa fa-sign-out"></i></span> <span class="s-text"><?php echo $this->lang->line('left_logout');?></span> </a> </li>
            <?php ?>
        </ul>
    </div>
</div>
<!-- menu end-->
