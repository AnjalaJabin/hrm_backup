<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['emp_id']) && $_GET['data']=='pay_payment' && $_GET['type']=='pay_payment'){ ?>
<?php
$grade_template = $this->Payroll_model->read_template_information($monthly_grade_id);
$hourly_template = $this->Payroll_model->read_hourly_wage_information($hourly_grade_id);
$payment_month = strtotime($payment_date);
$p_month = date('F Y',$payment_month);
if($payment_method==1){
  $p_method = 'Online';
} else if($payment_method==2){
  $p_method = 'PayPal';
} else if($payment_method==3) {
  $p_method = 'Payoneer';
} else if($payment_method==4){
  $p_method = 'Bank Transfer';
} else if($payment_method==5) {
  $p_method = 'Cheque';
} else {
  $p_method = 'Cash';
}
?>
<?php
$subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
$subdomain_name = $subdomain_arr[0]; //assigns the first part ( sub- domain )
$subdomain_name; // Print the sub domain
$accounts_url = 'https://'.$subdomain_name.'.corbuz.com/';
if($profile_picture!='' && $profile_picture!='no file') {
	$u_file = $accounts_url.'uploads/profile/'.$profile_picture;
} else {
	if($gender=='Male') { 
		$u_file = $accounts_url.'uploads/profile/default_male.jpg';
	} else {
		$u_file = $accounts_url.'uploads/profile/default_female.jpg';
	}
} ?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Employee Salary Details of <?php echo $p_month;?></h4>
</div>
<div class="modal-body">
  <div class="row row-md">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header text-uppercase"><b><?php echo $first_name.' '.$last_name;?></b></div>
        <div class="bg-white product-view">
          <div class="box-block">
            <div class="row">
              <div class="col-md-4 col-sm-5">
                <div class="pv-images mb-sm-0"> <img class="img-fluid" src="<?php echo $u_file;?>" alt=""> </div>
              </div>
              <div class="col-md-8 col-sm-7">
                <div class="pv-content">
                  <div class="table-responsive" data-pattern="priority-columns">
                    <table class="table-hover">
                      <tbody>
                        <tr>
                          <td><strong>EMP ID</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $employee_id;?></td>
                        </tr>
                        <tr>
                          <td><strong><?php echo $this->lang->line('left_department');?></strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $department_name;?></td>
                        </tr>
                        <tr>
                          <td><strong><?php echo $this->lang->line('left_designation');?></strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $designation_name;?></td>
                        </tr>
                        <tr>
                          <td><strong>Joining Date:</strong>:</td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td><?php echo $date_of_joining;?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-1">
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b>SALARY DETAILS</b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('xin_salary_month');?>: </strong></label>
                <?php echo $p_month;?> </div>
            </div>
            <?php if($gross_salary):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('xin_payroll_gross_salary');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($gross_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($overtime_amount!=0 || $overtime_amount!=''):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong>Overtime salary: </strong></label>
                <?php echo $this->Xin_model->currency_sign($overtime_amount);?> </div>
            </div>
            <?php endif;?>
            <?php if($hourly_rate):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('xin_payroll_hourly_rate');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($hourly_rate);?> </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('xin_total_hours_worked');?>: </strong></label>
                <?php echo $total_hours_work;?></div>
            </div>
            <?php endif;?>
            <?php if($is_payment==1):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('dashboard_xin_status');?>: </strong></label>
                <span class="tag tag-success"><?php echo $this->lang->line('xin_payment_paid');?></span></div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <?php if($house_rent_allowance>0 || $medical_allowance>0 || $travelling_allowance>0 || $telephone_allowance>0 || $allowance>0||$other_allowance>0): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b> <?php echo $this->lang->line('xin_payroll_allowances');?></b> </div>
        <div class="card-block">
          <blockquote class="card-blockquote">
            <div class="row m-b-1">
              <?php if($house_rent_allowance>0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong><?php echo $this->lang->line('xin_Payroll_house_rent_allowance');?>: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($house_rent_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($medical_allowance>0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong><?php echo $this->lang->line('xin_payroll_medical_allowance');?>: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($medical_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($travelling_allowance>0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong><?php echo $this->lang->line('xin_payroll_travel_allowance');?>: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($travelling_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($dearness_allowance>0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Telephone Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($telephone_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($other_allowance>0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Other Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($other_allowance);?> </div>
              </div>
              <?php endif;?>
            <?php if($allowance>0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Extra Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($allowance);?> </div>
              </div>
              <?php endif;?>
            </div>
          </blockquote>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if($provident_fund>0 || $tax_deduction>0 || $security_deposit>0 || $extra_deductions>0 || $leave_salary_deduct_amount>0): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b> <?php echo $this->lang->line('xin_deductions');?></b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <?php if($provident_fund>0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_provident_fund_de');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($provident_fund);?> </div>
            </div>
            <?php endif;?>
            <?php if($tax_deduction>0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_tax_deduction_de');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($tax_deduction);?> </div>
            </div>
            <?php endif;?>
            <?php if($security_deposit>0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_security_deposit');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($security_deposit);?> </div>
            </div>
            <?php endif;?>
            <?php if($leave_salary_deduct_amount>0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Leave Deducted Salary (<?php echo $leave_days; ?> Days): </strong></label>
                <?php echo $this->Xin_model->currency_sign($leave_salary_deduct_amount);?> </div>
            </div>
            <?php endif;?>
            <?php if($extra_deductions>0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Extra Deductions: </strong></label>
                <?php echo $this->Xin_model->currency_sign($extra_deductions);?> </div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if($employee_expenses>0): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
          <div class="card-header text-uppercase"><b> Employee Expenses:</b><?php echo $this->Xin_model->currency_sign(number_format($employee_expenses,2));?></div>

      </div>
    </div>
    <?php endif;?>
    <?php if($ticket_amount>0): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
          <div class="card-header text-uppercase"><b> Ticket Encashment:</b><?php echo $this->Xin_model->currency_sign(number_format($ticket_amount,2));?></div>

      </div>
    </div>
    <?php endif;?>
    <?php if($leave_salary>0): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
          <div class="card-header text-uppercase"><b> Leave Salary:</b><?php echo $this->Xin_model->currency_sign(number_format($leave_salary,2));?></div>

      </div>
    </div>
    <?php endif;?>
    <?php if(($house_rent_allowance!='' || $medical_allowance!='' || $travelling_allowance!='' || $dearness_allowance!='') && ($provident_fund!='' || $tax_deduction!='' || $security_deposit!='')){
		$col_sm = 'col-sm-12';
		$offset = 'offset-2md-3';
	} else {
		$col_sm = 'col-sm-12';
		$offset = '';
	}
	
	$total_allowances  = floatval($house_rent_allowance)+floatval($medical_allowance)+floatval($travelling_allowance)+floatval($telephone_allowance)+floatval($other_allowance);

	?>
    <div class="<?php echo $col_sm;?> col-xs-12 <?php echo $offset;?>">
      <div class="card">
        <div class="card-header text-uppercase"><b> <?php echo $this->lang->line('xin_payroll_total_salary_details');?></b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <?php if($gross_salary): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_gross_salary');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($gross_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($total_allowances>0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_total_allowance');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($total_allowances);?> </div>
            </div>
            <?php endif;?>
            <?php
            if($total_deductions>0): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_total_deduction');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($total_deductions);?> </div>
            </div>
            <?php endif;?>
            <?php if($net_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_net_salary');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($net_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($is_advance_salary_deduct==1): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_advance_deducted_salary');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($advance_salary_amount);?> </div>
            </div>
            <?php endif;?>
            <?php if($net_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_paid_amount');?>: </strong></label>
                <?php if($is_advance_salary_deduct==1): ?>
                <?php $re_paid_amount = $net_salary - $advance_salary_amount;?>
                <?php else:?>
                <?php $re_paid_amount = $net_salary;?>
                <?php endif;?>
                <?php echo $this->Xin_model->currency_sign($payment_amount);?> </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_gross_salary');?>: </strong></label>
                <?php 
				$gsalary = $total_hours_work * $hourly_rate;
				echo $this->Xin_model->currency_sign($gsalary);?>
              </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_net_salary');?>: </strong></label>
                <?php 
				$hrs_salary = $total_hours_work * $hourly_rate;
				echo $this->Xin_model->currency_sign($hrs_salary);?>
              </div>
            </div>
            <?php endif;?>
            <?php if($total_hours_work): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_paid_amount');?>: </strong></label>
                <?php 
				$hrs_sal = $total_hours_work * $hourly_rate;
				echo $this->Xin_model->currency_sign($hrs_sal);?>
              </div>
            </div>
            <?php endif;?>
            <?php if($net_salary): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payment_method');?>: </strong></label>
                <?php echo $p_method;?></div>
            </div>
            <?php endif;?>
            <?php if($net_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payment_comment');?>: </strong></label>
                <?php echo $comments;?></div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php }

