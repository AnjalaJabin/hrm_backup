<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['salary_template_id']) && $_GET['data']=='payroll'){
    $no_of_days =$this->Xin_model->get_number_of_days($_GET['date']);


    ?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_payroll_template');?></h4>
</div>
<form class="m-b-1" action="<?php echo site_url("payroll/update_template").'/'.$salary_template_id; ?>" method="post" name="update_template" id="update_template" autocomplete="off">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $salary_template_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $salary_grades;?>">
  <div class="modal-body">
    <div class="bg-white">
      <div class="box-block">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="salary_grades"><?php echo $this->lang->line('xin_name_of_template');?></label>
                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_name_of_template');?>" name="salary_grades" type="text" value="<?php echo $salary_grades;?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="basic_salary" class="control-label"><?php echo $this->lang->line('xin_payroll_basic_salary');?></label>
                  <input class="form-control m_salary" placeholder="<?php echo $this->lang->line('xin_payroll_basic_salary');?>" name="basic_salary" type="text" value="<?php echo $basic_salary;?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="overtime_rate" class="control-label"><?php echo $this->lang->line('xin_payroll_overtime_rate');?></label>
                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_payroll_overtime_rate');?>" name="overtime_rate" type="text" value="<?php echo $overtime_rate;?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="house_rent_allowance"><?php echo $this->lang->line('xin_Payroll_house_rent_allowance');?></label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="house_rent_allowance" type="text" value="<?php echo $house_rent_allowance;?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="medical_allowance"><?php echo $this->lang->line('xin_payroll_medical_allowance');?></label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="medical_allowance" type="text" value="<?php echo $medical_allowance;?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="travelling_allowance"><?php echo $this->lang->line('xin_payroll_travel_allowance');?></label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="travelling_allowance" type="text" value="<?php echo $travelling_allowance;?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="dearness_allowance"><?php echo $this->lang->line('xin_payroll_dearness_allowance');?></label>
                  <input class="form-control m_salary m_allowance" placeholder="Amount" name="dearness_allowance" type="text" value="<?php echo $dearness_allowance;?>">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
              </div>
              <div class="col-md-6">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="security_deposit"><?php echo $this->lang->line('xin_payroll_security_deposit');?></label>
                  <input class="form-control m_deduction" placeholder="Amount" name="security_deposit" type="text" value="<?php echo $security_deposit;?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-right">
            <h2><strong><?php echo $this->lang->line('xin_payroll_total_salary_details');?></strong></h2>
            <table class="table table-bordered custom-table">
              <tbody>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;"><?php echo $this->lang->line('xin_payroll_gross_salary');?> :</th>
                  <td class="hidden-print"><input type="text" name="gross_salary" readonly id="m_total" class="form-control" value="<?php echo $gross_salary;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;"><?php echo $this->lang->line('xin_payroll_total_allowance');?> :</th>
                  <td class="hidden-print"><input type="text" name="total_allowance" readonly id="m_total_allowance" class="form-control" value="<?php echo $total_allowance;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;"><?php echo $this->lang->line('xin_payroll_total_deduction');?> :</th>
                  <td class="hidden-print"><input type="text" name="total_deduction" readonly id="m_total_deduction" class="form-control" value="<?php echo $total_deduction;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;">Total Expenses:</th>
                  <td class="hidden-print"><input type="text" name="total_deduction" readonly id="total_expenses" class="form-control" value="<?php echo $total_expenses;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;">Ticket Expenses:</th>
                  <td class="hidden-print"><input type="text" name="total_deduction" readonly id="ticket_amount" class="form-control" value="<?php echo $ticket_amount;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;">Leave salary:</th>
                  <td class="hidden-print"><input type="text" name="total_deduction" readonly id="leave_salary" class="form-control" value="<?php echo $leave_salary;?>"></td>
                </tr>
                <tr>
                  <th class="col-sm-4 vertical-td" style="text-align:right;"><?php echo $this->lang->line('xin_payroll_net_salary');?> :</th>
                  <td class="hidden-print"><input type="text" name="net_salary" readonly id="m_net_salary" class="form-control" value="<?php echo $net_salary;?>"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("payroll/template_list") ?>",
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 

		/* Edit data */
		$("#update_template").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=payroll&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						xin_table.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
	$(document).on("keyup", function () {
	var sum_total = 0;
	var deduction = 0;
	var net_salary = 0;
	var allowance = 0;
	$(".m_salary").each(function () {
		sum_total += +$(this).val();
	});
	
	$(".m_deduction").each(function () {
		deduction += +$(this).val();
	});
	
	$(".m_allowance").each(function () {
		allowance += +$(this).val();
	});
	
	$("#m_total").val(sum_total);
	$("#m_total_deduction").val(deduction);
	$("#m_total_allowance").val(allowance);
	
	var net_salary = sum_total - deduction;
	$("#m_net_salary").val(net_salary);
	});
  </script>
<?php }
else if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payroll_template' && $_GET['type']=='payroll_template'){ ?>
<?php
$grade_template = $this->Payroll_model->read_salary_information_by_date($_GET['employee_id'],$_GET['date']);
    $total_expenses =$this->Payroll_model->get_expenses_by_month_user($_GET['employee_id'],$_GET['date']);
    $ticket_amount =$this->Payroll_model->get_tickets_by_month_user($_GET['employee_id'],$_GET['date']);
    $leave_salary =$this->Payroll_model->get_leave_salary_by_month_user($_GET['employee_id'],$_GET['date']);
    $no_of_days =$this->Xin_model->get_number_of_days($_GET['date']);


    ?>
<?php
$subdomain_arr = explode('.', $_SERVER['HTTP_HOST'], 2); //creates the various parts
$subdomain_name = $subdomain_arr[0]; //assigns the first part (  sub- domain )
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
<div class="modal-header animated fadeInRight">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Employee Salary Details</h4>
</div>
<div class="modal-body animated fadeInRight">
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
                          <td><strong><?php echo $this->lang->line('xin_emp_id');?></strong>:</td>
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
                          <td><strong><?php echo $this->lang->line('xin_joining_date');?></strong>:</td>
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
        <div class="card-header text-uppercase"><b><?php echo $this->lang->line('xin_payroll_salary_details');?></b></div>
        <div class="card-block">
          <div class="row m-b-1">
            
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('xin_payroll_basic_salary');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->basic_salary);?></div>
            </div>
            <?php if($grade_template[0]->overtime_rate!=0 || $grade_template[0]->overtime_rate!=''):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('xin_overtime_per_hour');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->overtime_rate);?> </div>
            </div>
            <?php endif;?>
            <?php if(isset($_GET['mode']) && $_GET['mode'] == 'not_paid'):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('dashboard_xin_status');?>: </strong></label>
                <span class="tag tag-danger"><?php echo $this->lang->line('xin_not_paid');?></span></div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <?php if($grade_template[0]->house_rent_allowance!='' || $grade_template[0]->medical_allowance!='' || $grade_template[0]->travelling_allowance!='' || $grade_template[0]->dearness_allowance!=''): ?>
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b> <?php echo $this->lang->line('xin_payroll_allowances');?></b> </div>
        <div class="card-block">
          <blockquote class="card-blockquote">
            <div class="row m-b-1">
              <?php if($grade_template[0]->house_rent_allowance!='' || $grade_template[0]->house_rent_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong><?php echo $this->lang->line('xin_Payroll_house_rent_allowance');?>: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->house_rent_allowance);?></div>
              </div>
              <?php endif;?>
              <?php if($grade_template[0]->medical_allowance!='' || $grade_template[0]->medical_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong><?php echo $this->lang->line('xin_payroll_medical_allowance');?>: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->medical_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($grade_template[0]->travelling_allowance!='' || $grade_template[0]->travelling_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong><?php echo $this->lang->line('xin_payroll_travel_allowance');?>: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->travelling_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($grade_template[0]->telephone_allowance!='' || $grade_template[0]->telephone_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Telephone Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->telephone_allowance);?> </div>
              </div>
              <?php endif;?>
              <?php if($grade_template[0]->other_allowance!='' || $grade_template[0]->other_allowance!=0): ?>
              <div class="col-md-12">
                <div class="f">
                  <label for="name"><strong>Other Allowance: </strong></label>
                  <?php echo $this->Xin_model->currency_sign($grade_template[0]->other_allowance);?> </div>
              </div>
              <?php endif;?>
            </div>
          </blockquote>
        </div>
      </div>
    </div>
    <?php endif;?>
<!--    --><?php //if($grade_template[0]->provident_fund!='' || $grade_template[0]->tax_deduction!='' || $grade_template[0]->security_deposit!=0): ?>
<!--    <div class="col-sm-12 col-xs-12">-->
<!--      <div class="card">-->
<!--        <div class="card-header text-uppercase"><b> --><?php //echo $this->lang->line('xin_deductions');?><!--</b></div>-->
<!--        <div class="card-block">-->
<!--          <div class="row m-b-1">-->
<!--            --><?php //if($grade_template[0]->provident_fund!='' || $grade_template[0]->provident_fund!=0): ?>
<!--            <div class="col-md-12">-->
<!--              <div class="f">-->
<!--                <label for="name"><strong>--><?php //echo $this->lang->line('xin_payroll_provident_fund_de');?><!--: </strong></label>-->
<!--                --><?php //echo $this->Xin_model->currency_sign($grade_template[0]->provident_fund);?><!-- </div>-->
<!--            </div>-->
<!--            --><?php //endif;?>
<!--            --><?php //if($grade_template[0]->tax_deduction!='' || $grade_template[0]->tax_deduction!=0): ?>
<!--            <div class="col-md-12">-->
<!--              <div class="f">-->
<!--                <label for="name"><strong>--><?php //echo $this->lang->line('xin_payroll_tax_deduction_de');?><!--: </strong></label>-->
<!--                --><?php //echo $this->Xin_model->currency_sign($grade_template[0]->tax_deduction);?><!-- </div>-->
<!--            </div>-->
<!--            --><?php //endif;?>
<!--            --><?php //if($grade_template[0]->security_deposit!='' || $grade_template[0]->security_deposit!=0): ?>
<!--            <div class="col-md-12">-->
<!--              <div class="f">-->
<!--                <label for="name"><strong>--><?php //echo $this->lang->line('xin_payroll_security_deposit');?><!--: </strong></label>-->
<!--                --><?php //echo $this->Xin_model->currency_sign($grade_template[0]->security_deposit);?><!-- </div>-->
<!--            </div>-->
<!--            --><?php //endif;?>
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--    --><?php //endif;?>
    <?php if(($grade_template[0]->house_rent_allowance!='' || $grade_template[0]->medical_allowance!='' || $grade_template[0]->travelling_allowance!='' || $grade_template[0]->dearness_allowance!='') && ($grade_template[0]->provident_fund!='' || $grade_template[0]->tax_deduction!='' || $grade_template[0]->security_deposit!='')){
		$col_sm = 'col-sm-12';
		$offset = 'offset-2md-3';
	} else {
		$col_sm = 'col-sm-12';
		$offset = '';
	}
    //leave calculation
    $deduct_salary = 0;
    $payment_month = strtotime($_GET['date']);
    $p_month = date('F Y',$payment_month);
    $all_leave_types = $this->Timesheet_model->all_leave_types();
    $unpaid_leaves = 0;
    $deduct_leave_sal = 0;
    $unpaid_off=0;
    $leaves_per_yeartype=array();
    foreach($all_leave_types as $type) {
        $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id,$_GET['employee_id'],date('Y',$payment_month));
        if(($count_l>$type->days_per_year)&&($type->type_name!="Unpaid Leave"))
        {
            $unpaid_leaves = $unpaid_leaves+($count_l-$type->days_per_year);
            $count_l-=$type->days_per_year;
        }else{
            $unpaid_leaves += $count_l;
            $unpaid_off+=$count_l;

        }
        $leaves_per_yeartype[$type->leave_type_id]=$count_l;
    }

    if($unpaid_leaves>0)
    {
        $unpaid_leaves_count = $this->Timesheet_model->count_total_un_paid_leaves($_GET['employee_id']);
        if($unpaid_leaves_count<$unpaid_leaves)
        {
            $unpaid_leaves = $unpaid_leaves_count+$unpaid_off;
        }
    }
    $unpaid_off_per_month =0;
    foreach($all_leave_types as $type) {
//
        $count_per_month =$this->Timesheet_model->get_leave_days_month($_GET['employee_id'],$_GET['date'],$type->leave_type_id);
        if($count_per_month) {
            if ($type->type_name != "Unpaid Leave") {
                if ($leaves_per_yeartype[$type->leave_type_id] < $count_per_month)
                    $unpaid_off_per_month += $leaves_per_yeartype[$type->leave_type_id];
                else
                    $unpaid_off_per_month += $count_per_month;
            }
            else {
                $unpaid_off_per_month += $count_per_month;
            }
        }

    }
    $annual_leaves =$this->Timesheet_model->check_annual_leaves_for_employee($_GET['employee_id'],$_GET['date']);
    if($annual_leaves)
        $unpaid_off_per_month=$unpaid_off_per_month+$annual_leaves;


    $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($_GET['employee_id']);
    $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($_GET['employee_id']);
    if(!is_null($advance_salary)){
        $monthly_installment = $advance_salary[0]->monthly_installment;
        //check ifpaid
        $em_advance_amount = floatval($emp_value[0]->advance_amount);
        $em_total_paid = floatval($emp_value[0]->total_paid);
        if($em_advance_amount > $em_total_paid){
            if($monthly_installment=='' || $monthly_installment==0) {
                $re_amount = $em_advance_amount - $em_total_paid;

                $ntotal_paid = $emp_value[0]->total_paid;
                $nadvance = $emp_value[0]->advance_amount;
                $total_net_salary = $nadvance - $ntotal_paid;
                $advance_amount = $re_amount;
            } else {
                //
                $re_amount = $em_advance_amount - $em_total_paid;
                if($monthly_installment > $re_amount){
                    $advance_amount = $re_amount;
                } else {
                    $advance_amount = $monthly_installment;
                }
            }

        } else {

            $advance_amount = 0;
        }
    } else {
        $advance_amount = 0;
    }
    $loan_emi = 0;

    $loan = $this->Payroll_model->loan_by_employee_id($_GET['employee_id']);
    $emp_loan_value = $this->Payroll_model->get_paid_loan_by_employee_id($_GET['employee_id']);

    if(!is_null($loan)){
        $monthly_installment = $loan[0]->monthly_installment;
        $loan_emi = $loan[0]->advance_amount;
        $total_paid = $loan[0]->total_paid;
        //check ifpaid
        $em_advance_amount = floatval($loan[0]->advance_amount);
        $em_total_paid = floatval($loan[0]->total_paid);

        if($em_advance_amount > $em_total_paid){
            //
            $re_amount = $em_advance_amount - $em_total_paid;
            if($monthly_installment > $re_amount){
                $loan_emi = $re_amount;
            } else {
                $loan_emi = $monthly_installment;
            }

        } else {
            $loan_emi = 0;
        }
    } else {
        $loan_emi = 0;
    }
    $per_day_sal = ($grade_template[0]->basic_salary+$grade_template[0]->total_allowance)/$no_of_days;

    if($unpaid_off_per_month>0)
    {
        $unpaid_off_per_month>$no_of_days?$unpaid_off_per_month=$no_of_days:$unpaid_off_per_month=$unpaid_off_per_month;
        $deduct_leave_sal = round($per_day_sal*$unpaid_off_per_month);
    }
    $total_deductions = $deduct_leave_sal+$loan_emi+$advance_amount;

    ?>
    <?php if($total_deductions): ?>

        <div class="<?php echo $col_sm;?> col-xs-12 <?php echo $offset;?>">

          <div class="card">
              <div class="card-header text-uppercase"><b> Total Deductions:</b></div>
              <div class="card-block">
                  <div class="row m-b-1">
                      <?php if($advance_amount): ?>
                          <div class="col-md-12">
                              <div class="f">
                                  <label for="name"><strong>ADVANCE SALARY: </strong></label>
                                  <?php echo $this->Xin_model->currency_sign(number_format($advance_amount,2));?> </div>
                          </div>
                      <?php endif;?>
                      <?php if($loan_emi): ?>
                          <div class="col-md-12">
                              <div class="f">
                                  <label for="name"><strong>Loan EMI: </strong></label>
                                  <?php echo $this->Xin_model->currency_sign($loan_emi,2);?> </div>
                          </div>
                      <?php endif;?>
                      <?php if($deduct_leave_sal): ?>
                          <div class="col-md-12">
                              <div class="f">
                                  <label for="name"><strong>Leave Deductions: </strong></label>
                                  <?php echo $this->Xin_model->currency_sign($deduct_leave_sal,2);?> </div>
                          </div>
                      <?php endif;?>

                  </div>
              </div>
          </div>
      </div>
    <?php endif;?>

      <div class="<?php echo $col_sm;?> col-xs-12 <?php echo $offset;?>">
      <div class="card">
        <div class="card-header text-uppercase"><b> <?php echo $this->lang->line('xin_payroll_total_salary_details');?></b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <?php if($grade_template[0]->gross_salary!=''): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_gross_salary');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->gross_salary);?> </div>
            </div>
            <?php endif;?>
            <?php if($grade_template[0]->total_allowance && $grade_template[0]->total_allowance!='0'): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_total_allowance');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($grade_template[0]->total_allowance);?> </div>
            </div>
            <?php endif;?>
            <?php if($total_expenses && $total_expenses!='0'): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Total Expenses: </strong></label>
                <?php echo $this->Xin_model->currency_sign(number_format($total_expenses,2));?> </div>
            </div>
              <?php endif;

             if($ticket_amount && $ticket_amount!='0'): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Ticket Amount: </strong></label>
                <?php echo $this->Xin_model->currency_sign(number_format($ticket_amount,2));?> </div>
            </div>
            <?php endif;
             if($leave_salary && $leave_salary!='0'): ?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong>Leave Salary: </strong></label>
                <?php echo $this->Xin_model->currency_sign(number_format($leave_salary,2));?> </div>
            </div>
            <?php endif;
               if($total_deductions && $total_deductions!='0'): ?>
                  <div class="col-md-12">
                      <div class="f">
                          <label for="name"><strong>Total Deductions: </strong></label>
                          <?php echo $this->Xin_model->currency_sign(number_format(($advance_amount+$loan_emi+$deduct_leave_sal),2));?> </div>
                  </div>
              <?php endif;?>

            <?php if($grade_template[0]->net_salary!=''):
                $total_n =$grade_template[0]->basic_salary-$total_deductions+$total_expenses+$grade_template[0]->total_allowance;
            if($ticket_amount)
                $total_n+=$ticket_amount;
            if($leave_salary)
                $total_n+=$leave_salary;?>
            <div class="col-md-12">
              <div class="f">
                <label for="name"><strong><?php echo $this->lang->line('xin_payroll_net_salary');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign(number_format($total_n,2));

                ?>
              </div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } else if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='hourlywages' && $_GET['type']=='hourlywages'){ ?>
<?php
$grade_template = $this->Payroll_model->read_template_information($monthly_grade_id);
$hourly_template = $this->Payroll_model->read_hourly_wage_information($hourly_grade_id);
?>
<?php
if($profile_picture!='' && $profile_picture!='no file') {
	$u_file = 'uploads/profile/'.$profile_picture;
} else {
	if($gender=='Male') { 
		$u_file = 'uploads/profile/default_male.jpg';
	} else {
		$u_file = 'uploads/profile/default_female.jpg';
	}
} ?>
<div class="modal-header animated fadeInRight">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_payroll_employee_hourly_wages');?></h4>
</div>
<div class="modal-body animated fadeInRight">
  <div class="row row-md">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header text-uppercase"><b><?php echo $first_name.' '.$last_name;?></b></div>
        <div class="bg-white product-view">
          <div class="box-block">
            <div class="row">
              <div class="col-md-4 col-sm-5">
                <div class="pv-images mb-sm-0"> <img class="img-fluid" src="<?php echo base_url().$u_file;?>" alt=""> </div>
              </div>
              <div class="col-md-8 col-sm-7">
                <div class="pv-content">
                  <div class="table-responsive" data-pattern="priority-columns">
                    <table class="table-hover">
                      <tbody>
                        <tr>
                          <td><strong><?php echo $this->lang->line('xin_emp_id');?></strong>:</td>
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
                          <td><strong><?php echo $this->lang->line('xin_joining_date');?></strong>:</td>
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
  <div class="form-group row"> 
    <!-- ********************************* Salary Details Panel ***********************-->
    <div class="col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-header text-uppercase"><b><?php echo $this->lang->line('xin_payroll_total_salary_details');?></b></div>
        <div class="card-block">
          <div class="row m-b-1">
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('xin_payroll_hourly_wage');?>: </strong></label>
                <?php echo $hourly_template[0]->hourly_grade;?> </div>
            </div>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('xin_payroll_hourly_rate');?>: </strong></label>
                <?php echo $this->Xin_model->currency_sign($hourly_template[0]->hourly_rate);?> </div>
            </div>
            <?php if(isset($_GET['mode']) && $_GET['mode'] == 'not_paid'):?>
            <div class="col-md-12">
              <div class="f">
                <label for="name" class="control-label" style="text-align:right;"><strong><?php echo $this->lang->line('dashboard_xin_status');?>: </strong></label>
                <span class="tag tag-danger"><?php echo $this->lang->line('xin_not_paid');?></span></div>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php }
?>
