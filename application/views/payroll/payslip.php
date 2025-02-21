<?php
/* Payslip view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php
	$gd = '';
	if($hourly_rate == '') {
		$gd = 'sl';
	} else {
		$gd = 'hr';
	}
?>

<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white">
      <h2><strong><?php echo $this->lang->line('xin_payslip');?></strong>
        <div class="add-record-btn"> <a href="<?php echo site_url();?>payroll/pdf_create_new/<?php echo $gd;?>/<?php echo $make_payment_id;?>/" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $this->lang->line('xin_pdf');?>"><span <i="" class="fa fa-file-pdf-o"></span></a>
        <a target="blank" href="<?php echo site_url();?>payroll/print_payslip/<?php echo $gd;?>/<?php echo $make_payment_id;?>/" class="btn btn-primary btn-md" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print"><span <i="" class="fa fa-print"></span></a> </div>
      </h2>
      <div class="panel">
        <div class="panel-heading p-b-none">
          <p><strong><?php echo $this->lang->line('xin_salary_month');?>: </strong><?php echo date("F, Y", strtotime($payment_date));?></p>
        </div>
        <div class="panel-body p-none m-b-10">
          <table class="table table-no-border table-condensed">
            <tbody>
              <tr>
                <td><strong class="help-split"><?php echo $this->lang->line('dashboard_employee_id');?>: </strong>#<?php echo $employee_id;?></td>
                <td><strong class="help-split"><?php echo $this->lang->line('xin_employee_name');?>: </strong><?php echo $first_name.' '.$last_name;?></td>
                <td><strong class="help-split"><?php echo $this->lang->line('xin_payslip_number');?>: </strong><?php echo $make_payment_id;?></td>
              </tr>
              <tr>
                <td><strong class="help-split"><?php echo $this->lang->line('xin_phone');?>: </strong><?php echo $contact_no;?></td>
                <td><strong class="help-split"><?php echo $this->lang->line('xin_joining_date');?>: </strong><?php echo $this->Xin_model->set_date_format($date_of_joining);?></td>
                <td><strong class="help-split"><?php echo $this->lang->line('xin_payslip_payment_by');?>: </strong><?php echo $payment_method;?></td>
              </tr>
              <tr>
                <td><strong class="help-split"><?php echo $this->lang->line('left_department');?>: </strong><?php echo $department_name;?></td>
                <td><strong class="help-split"><?php echo $this->lang->line('left_designation');?>: </strong><?php echo $designation_name;?></td>
                <td><strong class="help-split">&nbsp;</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row  m-b-1">
  <div class="col-md-6">
    <div class="box box-block bg-white">
      <div class="panel">
        <div class="panel-heading p-b-none">
          <h4 class="m-b-10"><strong><?php echo $this->lang->line('xin_payment_details');?></strong></h4>
        </div>
        <div class="panel-body p-none">
          <table class="table table-condensed">
            <tbody>
              <?php if($hourly_rate!=0 || $hourly_rate!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payroll_hourly_rate');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($hourly_rate);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_hours_work!=0 || $total_hours_work!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_total_hours_worked');?>:</strong> <span class="pull-right"><?php echo $total_hours_work;?></span></td>
              </tr>
              <?php endif;?>
              <?php if($overtime_amount!=0 || $overtime_amount!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_Payslip_overtime_salary');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($overtime_amount);?> </span></td>
              </tr>
              <?php endif;?><?php if($employee_expenses!=0 || $employee_expenses!=''):?>
              <tr>
                <td><strong>Employee Expenses:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($employee_expenses);?> </span></td>
              </tr>
              <?php endif;?><?php if($ticket_amount!=0 || $ticket_amount!=''):?>
              <tr>
                <td><strong>Ticket Encashment:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($ticket_amount);?> </span></td>
              </tr>
              <?php endif;?><?php if($leave_salary!=0 || $leave_salary!=''):?>
              <tr>
                <td><strong>Leave Salary:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($leave_salary);?> </span></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="box box-block bg-white">
      <div class="panel">
        <div class="panel-heading p-b-none">
          <h4 class="m-b-10"><strong><?php echo $this->lang->line('xin_payslip_earning');?></strong></h4>
        </div>
        <div class="panel-body p-none">
          <?php if($hourly_rate==0 && $hourly_rate==''):?>
          <table class="table table-condensed">
            <tbody>
              <?php if($overtime_rate!=0 || $overtime_rate!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payroll_gross_salary');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($gross_salary);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_allowances!=0 || $total_allowances!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payroll_total_allowance');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($total_allowances);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_deductions>0):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payroll_total_deduction');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($total_deductions);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($net_salary!=0 || $net_salary!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payroll_net_salary');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($net_salary);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($is_advance_salary_deduct==1):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_advance_deducted_salary');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($advance_salary_amount);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($loan_emi>0):?>
              <tr>
                <td><strong>Loan EMI:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($loan_emi);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($leave_salary_deduct_amount>0):?>
              <tr>
                <td><strong>Leaves Deducted Salary (<?php echo $leave_days ?> Days):</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign(round($leave_salary_deduct_amount));?></span></td>
              </tr>
              <?php endif;?>
              <?php if($allowance>0):?>
              <tr>
                <td><strong>Extra Allowance :</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign(round($allowance));?></span></td>
              </tr>
              <?php endif;?>
              <?php if($extra_deductions>0):?>
              <tr>
                <td><strong>Extra Deductions :</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign(round($extra_deductions));?></span></td>
              </tr>
              <?php endif;?>
              <?php if($net_salary!=0 || $net_salary!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_paid_amount');?>:</strong> <span class="pull-right">
                <?php if($is_advance_salary_deduct==1): ?>
                <?php $re_paid_amount = $net_salary - $advance_salary_amount;?>
                <?php else:?>
                <?php $re_paid_amount = $net_salary;?>
                <?php endif;?>
				<?php echo $this->Xin_model->currency_sign($payment_amount);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($payment_method):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payment_method');?>:</strong> <span class="pull-right"><?php echo $payment_method;?></span></td>
              </tr>
              <?php endif;?>
              <?php if($net_salary!=0 || $net_salary!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payment_comment');?>:</strong> <span class="pull-right"><?php echo $comments;?></span></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
          <?php else:?>
          <table class="table table-condensed">
            <tbody>
              <?php if($payment_amount!=0 || $payment_amount!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payroll_gross_salary');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($payment_amount);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_hours_work!=0 || $total_hours_work!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payroll_net_salary');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($payment_amount);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_hours_work!=0 || $total_hours_work!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_paid_amount');?>:</strong> <span class="pull-right"><?php echo $this->Xin_model->currency_sign($payment_amount);?></span></td>
              </tr>
              <?php endif;?>
              <?php if($payment_method):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payment_method');?>:</strong> <span class="pull-right"><?php echo $payment_method;?></span></td>
              </tr>
              <?php endif;?>
              <?php if($total_hours_work!=0 || $total_hours_work!=''):?>
              <tr>
                <td><strong><?php echo $this->lang->line('xin_payment_comment');?>:</strong> <span class="pull-right"><?php echo $comments;?></span></td>
              </tr>
              <?php endif;?>
            </tbody>
          </table>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- pd--> 
