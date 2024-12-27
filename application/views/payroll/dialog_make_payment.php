<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payment' && $_GET['type']=='monthly_payment'){ ?>
    <?php
    $payment_month = strtotime($this->input->get('pay_date'));
    $monthy =date('Y-m',$payment_month);
    $no_of_days =$this->Xin_model->get_number_of_days($monthy);
    $p_month = date('F Y',$payment_month);

    $user = $this->Xin_model->read_user_info($_GET['employee_id']);
    $payee_name = $user[0]->first_name.' '.$user[0]->last_name;

    ?>
    <?php
// get monthly installment
//$user = $this->Xin_model->read_user_info($_GET['employee_id']);

//Checking for unpaid salary
    $all_leave_types = $this->Timesheet_model->all_leave_types();
    $unpaid_leaves = 0;
    $deduct_leave_sal = 0;
    $unpaid_off=0;
    $leaves_per_yeartype=array();
    foreach($all_leave_types as $type) {
        $count_l = $this->Timesheet_model->count_total_leaves($type->leave_type_id,$_GET['employee_id'],date('Y',$payment_month));
//                        echo $this->db->last_query();
//                        die;
        if(($count_l>$type->days_per_year)&&($type->type_name!="Unpaid Leave"))
        {
            $unpaid_leaves = $unpaid_leaves+($count_l-$type->days_per_year);
            $count_l-=$type->days_per_year;
        }else if($type->type_name=="Unpaid Leave"){
            $unpaid_leaves+=$count_l;
            $unpaid_off+=$count_l;

        }else{
            $unpaid_leaves += 0;
            $count_l=0;
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
                if ($leaves_per_yeartype[$type->leave_type_id] < $count_per_month) {
                    $unpaid_off_per_month += $leaves_per_yeartype[$type->leave_type_id];
                }
                else {

                    $unpaid_off_per_month += $count_per_month;

                }
            }
            else {

                $unpaid_off_per_month += $count_per_month;
            }
        }

    }
    $annual_leaves =$this->Timesheet_model->check_annual_leaves_for_employee($_GET['employee_id'],$_GET['date']);
    if($annual_leaves)
        $unpaid_off_per_month=$unpaid_off_per_month+$annual_leaves;


// get advance salary
    $advance_salary = $this->Payroll_model->advance_salary_by_employee_id($_GET['employee_id']);
    $emp_value = $this->Payroll_model->get_paid_salary_by_employee_id($_GET['employee_id']);

    $pay_amount2 = $net_salary;

    if(!is_null($advance_salary)){
        $monthly_installment = $advance_salary[0]->monthly_installment;
        $advance_amount = $advance_salary[0]->advance_amount;
        $total_paid = $advance_salary[0]->total_paid;
        //check ifpaid
        $em_advance_amount = floatval($advance_salary[0]->advance_amount);
        $em_total_paid = floatval($advance_salary[0]->total_paid);

        if($em_advance_amount > $em_total_paid){
            if($monthly_installment=='' || $monthly_installment==0) {

                $ntotal_paid = floatval($emp_value[0]->total_paid);
                $nadvance = floatval($emp_value[0]->advance_amount);
                $total_net_salary = $nadvance - $ntotal_paid;
                $pay_amount = $net_salary - $total_net_salary;
                $advance_amount = $total_net_salary;
            } else {
                //
                $re_amount = $em_advance_amount - $em_total_paid;
                if($monthly_installment > $re_amount){
                    $advance_amount = $re_amount;
                    $total_net_salary = $net_salary - $re_amount;
                    $pay_amount = $net_salary - $re_amount;
                } else {
                    $advance_amount = $monthly_installment;
                    $total_net_salary = $net_salary - $monthly_installment;
                    $pay_amount = $net_salary - $monthly_installment;
                }
            }

        } else {
            $total_net_salary = $net_salary - 0;
            $pay_amount = $net_salary - 0;
            $advance_amount = 0;
        }
    } else {
        $pay_amount = $net_salary - 0;
        $total_net_salary = $net_salary - 0;
        $advance_amount = 0;
    }
//    if($total_expenses)
//        $net_salary +=$total_expenses;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
// get advance salary
    $loan = $this->Payroll_model->loan_by_employee_id($_GET['employee_id']);
    $emp_loan_value = $this->Payroll_model->get_paid_loan_by_employee_id($_GET['employee_id']);

    if(!is_null($loan)){
        $monthly_installment = $loan[0]->monthly_installment;
        $loan_emi = floatval($loan[0]->advance_amount);
        $total_paid = floatval($loan[0]->total_paid);
        //check ifpaid
        $em_advance_amount = $loan_emi;
        $em_total_paid = $total_paid;

        if($em_advance_amount > $em_total_paid){
            //
            $re_amount = $em_advance_amount - $em_total_paid;
            if($monthly_installment > $re_amount){
                $loan_emi = $re_amount;
                $total_net_salary = $pay_amount - $re_amount;
                $pay_amount = $pay_amount - $re_amount;
            } else {
                $loan_emi = $monthly_installment;
                $total_net_salary = $pay_amount - $monthly_installment;
                $pay_amount = $pay_amount - $monthly_installment;
            }

        } else {
            $total_net_salary = $pay_amount - 0;
            $pay_amount = $pay_amount - 0;
            $loan_emi = 0;
        }
    } else {
        $pay_amount = $pay_amount - 0;
        $total_net_salary = $pay_amount - 0;
        $loan_emi = 0;
    }
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
    $total_allow = floatval($house_rent_allowance) + floatval($medical_allowance) + floatval($travelling_allowance) + floatval($other_allowance) + floatval($telephone_allowance);

    $per_day_sal = ($basic_salary+$total_allow)/$no_of_days;
    $per_day_basic =floatval($basic_salary)/30;


    if($unpaid_off_per_month>0)
    {
        $unpaid_off_per_month>$no_of_days?$unpaid_off_per_month=$no_of_days:$unpaid_off_per_month=$unpaid_off_per_month;

        $deduct_leave_sal = round($per_day_sal*$unpaid_off_per_month);
        $pay_amount = round($pay_amount - $deduct_leave_sal);
    }
    $total_deductions = floatval($deduct_leave_sal)+floatval($loan_emi)+floatval($advance_amount);

    $total_net = floatval($basic_salary)+floatval($total_allowance)-floatval($total_deductions)+floatval($total_expenses);
    if($ticket_amount)
        $total_net+=floatval($ticket_amount);
    if($leave_salary)
        $total_net+=floatval($leave_salary);

    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><strong>Payment For</strong> <?php echo '<span class="text-success">('.$payee_name.')</span> '.$p_month;?></h4>
    </div>
    <div class="modal-body">
        <form class="m-b-1" action="<?php echo site_url("payroll/add_pay_monthly") ?>" method="post" name="pay_monthly" id="pay_monthly">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" value="<?php echo $per_day_sal; ?>" id="per_day_salary"/>
                        <input type="hidden" value="<?php echo $per_day_basic; ?>" id="per_day_basic"/>
                        <input type="hidden" name="department_id" value="<?php echo $department_id;?>" />
                        <input type="hidden" name="company_id" value="<?php echo $company_id;?>" />
                        <input type="hidden" name="location_id" value="<?php echo $location_id;?>" />
                        <input type="hidden" name="designation_id" value="<?php echo $designation_id;?>" />
                        <label for="name">Basic Salary</label>
                        <input type="text" name="gross_salary" class="form-control" value="<?php echo $basic_salary;?>" readonly>
                        <input type="hidden" id="emp_id" value="<?php echo $user_id?>" name="emp_id">
                        <input type="hidden" value="<?php echo $user_id;?>" name="u_id">
                        <input type="hidden" value="<?php echo $basic_salary;?>" name="basic_salary">
                        <input type="hidden" value="<?php echo $this->input->get('pay_date');?>" name="pay_date" id="pay_date">
                    </div>
                </div>
            </div>
            <input type="hidden" name="working_hours" id="working_hours" class="form-control" value="<?php echo $working_hours;?>">

            <?php if($overtime_rate!=''): ?>
                <input type="hidden" name="overtime_rate" id="overtime_rate" value="<?php echo $overtime_rate;?>">
            <?php else:?>
                <input type="hidden" name="overtime_rate" class="form-control" value="0">
            <?php endif;?>
            <?php if($house_rent_allowance!=''): ?>
                <input type="hidden" name="house_rent_allowance" value="<?php echo $house_rent_allowance;?>">
            <?php else:?>
                <input type="hidden" name="house_rent_allowance" class="form-control" value="0">
            <?php endif;?>
            <?php if($other_allowance!=''): ?>
                <input type="hidden" name="other_allowance" value="<?php echo $other_allowance;?>">
            <?php else:?>
                <input type="hidden" name="other_allowance" class="form-control" value="0">
            <?php endif;?>
            <?php if($telephone_allowance!=''): ?>
                <input type="hidden" name="telephone_allowance" value="<?php echo $telephone_allowance;?>">
            <?php else:?>
                <input type="hidden" name="telephone_allowance" class="form-control" value="0">
            <?php endif;?>
            <?php if($medical_allowance!=''): ?>
                <input type="hidden" name="medical_allowance" value="<?php echo $medical_allowance;?>">
            <?php else:?>
                <input type="hidden" name="medical_allowance" class="form-control" value="0">
            <?php endif;?>
            <?php if($travelling_allowance!=''): ?>
                <input type="hidden" name="travelling_allowance" value="<?php echo $travelling_allowance;?>">
            <?php else:?>
                <input type="hidden" name="travelling_allowance" class="form-control" value="0">
            <?php endif;?>
            <?php if($provident_fund!=''): ?>
                <input type="hidden" name="provident_fund" value="<?php echo $provident_fund;?>">
            <?php else:?>
                <input type="hidden" name="provident_fund" class="form-control" value="0">
            <?php endif;?>
            <?php if($tax_deduction!=''): ?>
                <input type="hidden" name="tax_deduction" value="<?php echo $tax_deduction;?>">
            <?php else:?>
                <input type="hidden" name="tax_deduction" class="form-control" value="0">
            <?php endif;?>
            <?php if($security_deposit!=''): ?>
                <input type="hidden" name="security_deposit" value="<?php echo $security_deposit;?>">
            <?php else:?>
                <input type="hidden" name="security_deposit" class="form-control" value="0">
            <?php endif;?>
            <?php if($house_rent_allowance!='' ||$other_allowance!='' ||$telephone_allowance!='' || $medical_allowance!='' || $travelling_allowance!='' ): ?>
                <?php if($house_rent_allowance==0): $house_rent_allowance = 0; endif;?>
                <?php if($medical_allowance==0): $medical_allowance = 0; endif;?>
                <?php if($other_allowance==0): $other_allowance = 0; endif;?>
                <?php if($telephone_allowance==0): $telephone_allowance = 0; endif;?>
                <?php if($travelling_allowance==0): $travelling_allowance = 0; endif;?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Total Allowance</label>
                            <input type="text" name="total_allowances" class="form-control" value="<?php echo $total_allow;?>" readonly>
                        </div>
                    </div>
                </div>
            <?php else:?>
                <input type="hidden" name="total_allowances" class="form-control" value="0">
            <?php endif;?>
            <!--    --><?php //if($total_deductions!='' && $total_deductions!=0): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Total Deduction</label>
                        <input type="text" id="total_deduction" name="total_deductions" class="form-control" value="<?php echo $total_deductions;?>" readonly>
                    </div>
                </div>
            </div>
            <!--    --><?php //else:?>
            <!--    <input type="hidden" name="total_deductions" class="form-control" value="0">-->
            <!--    --><?php //endif;?>
            <?php if($total_expenses):?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Total Expenses</label>
                            <input type="text" id="expenses" name="total_expenses" class="form-control" value="<?php echo round($total_expenses,2);?>" readonly>
                        </div>
                    </div>
                </div>
            <?php else:?>
                <input type="hidden" name="total_expenses" class="form-control" value="0">
            <?php endif;?>
            <?php if($ticket_amount):?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Ticket amount</label>
                            <input type="text" id="ticket_amount" name="ticket_amount" class="form-control" value="<?php echo round($ticket_amount,2);?>" readonly>
                        </div>
                    </div>
                </div>
            <?php else:?>
                <input type="hidden" name="ticket_amount" class="form-control" value="0">
            <?php endif;?>
            <?php if($leave_salary):?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Leave Salary</label>
                            <input type="text" id="leave_salary" name="leave_salary" class="form-control" value="<?php echo round($leave_salary,2);?>" readonly>
                        </div>
                    </div>
                </div>
            <?php else:?>
                <input type="hidden" name="leave_salary" class="form-control" value="0">
            <?php endif;?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Net Salary</label>
                        <input type="text" name="net_salary" class="form-control" value="<?php echo $net_salary;?>" readonly>
                    </div>
                </div>
            </div>
            <?php if($advance_amount!=0 || $loan_emi!=0):?>
                <div class="row">
                    <?php if($advance_amount!=0):?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Advance Deducted Salary</label>
                                <input type="text" class="form-control" name="advance_amount" id="advance_amount" value="<?php echo $advance_amount;?>">
                            </div>
                        </div>
                    <?php endif;?>
                    <?php if($loan_emi!=0):?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Loan EMI</label>
                                <input type="text" class="form-control" name="loan_emi" id="loan_emi" value="<?php echo $loan_emi;?>">
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            <?php endif;?>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Leave Days</label>
                        <input type="text" class="form-control" name="leave_days" value="<?php echo $unpaid_off_per_month;?>" id="leave_days">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Leave Deducted Salary</label>
                        <input type="text" class="form-control" name="leave_amount" value="<?php echo $deduct_leave_sal;?>" readonly id="leave_amount">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name"> Overtime Hours</label>
                        <input type="text" class="form-control" id="overtime_hours" name="overtime_hours" value="">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Overtime Salary</label>
                        <input type="text" class="form-control" id="overtime_salary" name="overtime_salary" value="" readonly >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Extra Allowance</label>
                        <input type="text" class="form-control" name="allowance" value="0" id="allowance">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Extra Deductions</label>
                        <input type="text" class="form-control" name="extra_deductions" value="0" id="extra_deductions">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Payment Amount</label>
                        <input type="text" name="payment_amount" class="form-control" value="<?php echo $total_net;?>" readonly id="payment_amount">
                        <input type="hidden" class="form-control" value="<?php echo $net_salary;?>" id="act_payment_amount">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_method"><?php echo $this->lang->line('xin_payment_method');?></label>
                        <?php $bank_details =$this->Payroll_model->read_bank_account_information_user($user[0]->user_id);
                        if($bank_details) {
                            $selectedbank="selected";
                            $selectcash ='';
                        }else{
                            $selectedbank="";
                            $selectcash ='selected';
                        }


                        ?>
                        <select name="payment_method" class="select2" data-plugin="select_hrm" data-placeholder="Chose Payment Method">
                            <option value="">&nbsp;</option>
                            <!--            <option value="1">Online</option>-->
                            <!--            <option value="2">PayPal</option>-->
                            <!--            <option value="3">Payoneer</option>-->
                            <option <?php echo $selectedbank;?> value="4">Bank Transfer</option>
                            <option  value="5">Cheque</option>
                            <option <?php echo $selectcash;?> value="6">Cash</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Comments</label>
                        <input type="text" class="form-control" name="comments">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary save">Pay</button>
        </form>
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            toastr.options.closeButton = true;
            toastr.options.progressBar = false;
            toastr.options.timeOut = 3000;
            toastr.options.preventDuplicates = true;
            toastr.options.positionClass = "toast-bottom-right";
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            payment_amount_calc();
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });

            // On page load: datatable
            var xin_table = $('#xin_table').dataTable({
                "bDestroy": true,
                "pageLength": 100,
                dom: 'Blfrtip',
                "order": [[1, "asc"]] ,
                select: {
                    style: 'multi', // Allow multiple row selection
                    // selector: 'td:first-child' // Allow selection when clicking on the first column
                },
                buttons: [

                    {
                        extend: 'copy',
                        text: 'Copy',
                        action: function(e, dt, button, config) {
                            if (dt.rows({ selected: true }).any()) {
                                config.exportOptions.rows = { selected: true };
                            } else {
                                config.exportOptions.rows = { selected: null };
                            }
                            $.fn.dataTable.ext.buttons.copyHtml5.action(e, dt, button, config);
                        },
                        exportOptions: {
                            columns: ':visible:not(.no-export)',

                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        action: function(e, dt, button, config) {
                            if (dt.rows({ selected: true }).any()) {
                                config.exportOptions.rows = { selected: true };
                            } else {
                                config.exportOptions.rows = { selected: null };
                            }
                            $.fn.dataTable.ext.buttons.csvHtml5.action(e, dt, button, config);
                        },
                        exportOptions: {
                            columns: ':visible:not(.no-export)',

                        }
                    },

                    {
                        extend: 'excel',
                        text: 'Excel',
                        action: function(e, dt, button, config) {
                            if (dt.rows({ selected: true }).any()) {
                                config.exportOptions.rows = { selected: true };
                            } else {
                                config.exportOptions.rows = { selected: null };
                            }
                            $.fn.dataTable.ext.buttons.excelHtml5.action(e, dt, button, config);
                        },
                        exportOptions: {
                            columns: ':visible:not(.no-export)',
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        action: function(e, dt, button, config) {
                            if (dt.rows({ selected: true }).any()) {
                                config.exportOptions.rows = { selected: true };
                            } else {
                                config.exportOptions.rows = { selected: null };
                            }
                            $.fn.dataTable.ext.buttons.pdfHtml5.action(e, dt, button, config);
                        },
                        exportOptions: {
                            columns: ':visible:not(.no-export)',

                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        action: function(e, dt, button, config) {
                            if (dt.rows({ selected: true }).any()) {
                                config.exportOptions.rows = { selected: true };
                            } else {
                                config.exportOptions.rows = { selected: null };
                            }
                            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                        },
                        exportOptions: {
                            columns: ':visible:not(.no-export)'
                        }
                    },
                ],
                "ajax": {
                    url : "<?php echo site_url("payroll/payslip_list") ?>?employee_id=0&month_year=<?php echo $this->input->get('pay_date');?>"+"&dept="+department_id,
                    type : 'GET'
                },
                "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "columnDefs": [ {
                    "targets": 0,
                    "orderable": false
                } ,
                    {
                        "targets": 17, // Index of the column to hide
                        "visible": false
                    }
                ]
            });

            $("#pay_monthly").submit(function(e){

                /*Form Submit*/
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=11&data=monthly&add_type=add_monthly_payment&form="+action,
                    cache: false,
                    success: function (JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.emo_monthly_pay').modal('toggle');
                            xin_table.api().ajax.reload(function(){
                                toastr.success(JSON.result);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });

            $('#leave_days').keyup(function() {
                var leave_days = $(this).val();
                var per_day_sal= $('#per_day_salary').val();
                if(leave_days>0)
                {
                    var leave_amount = (parseFloat(leave_days)*parseFloat(per_day_sal)).toFixed(2);
                    $('#leave_amount').val(leave_amount);
                    payment_amount_calc();
                    total_deduction_calc();
                }
                else
                {
                    $('#leave_amount').val('0');
                    payment_amount_calc();
                    total_deduction_calc();
                }
            });
            $('#loan_emi').keyup(function() {
                payment_amount_calc();
                total_deduction_calc();

            });
            $('#advance_amount').keyup(function() {
                payment_amount_calc();
                total_deduction_calc();

            });
            $('#extra_deductions').keyup(function() {
                payment_amount_calc();
                total_deduction_calc();

            });
            $('#overtime_hours').keyup(function() {
                var ot_hours = $(this).val();
                var ot_val = $('#overtime_rate').val();
                var working_hours = $('#working_hours').val();
                var per_day_sal= $('#per_day_basic').val();
                console.log(ot_val);
                console.log(per_day_sal);
                console.log(working_hours);

                if((ot_hours>0)&&(ot_val>0)&&(ot_val!=undefined))
                {
                    var ot_amount = (parseFloat(ot_hours)*parseFloat(ot_val)*(parseFloat(per_day_sal)/parseFloat(working_hours))).toFixed(2);
                    $('#overtime_salary').val(ot_amount);
                    payment_amount_calc();
                }
                else
                {
                    $('#overtime_salary').val('0');
                    payment_amount_calc();
                }
            });
            function total_deduction_calc(){
                var advance_amount = $('#advance_amount').val();
                var loan_emi = $('#loan_emi').val();

                var leave_amount = $('#leave_amount').val();
                var extra_deductions = $('#extra_deductions').val();
                if(extra_deductions>0){  }else{ extra_deductions = 0; }

                if(advance_amount>0){  }else{ advance_amount = 0; }
                if(loan_emi>0){  }else{ loan_emi = 0; }
                if(leave_amount>0){  }else{ leave_amount = 0; }

                var payment_amount = parseInt(leave_amount)+parseInt(advance_amount)+parseInt(loan_emi)+parseInt(extra_deductions);
                $('#total_deduction').val(payment_amount);


            }
            function payment_amount_calc(){
                var advance_amount = $('#advance_amount').val();
                var loan_emi = $('#loan_emi').val();

                var leave_amount = $('#leave_amount').val();
                var act_payment_amount = $('#act_payment_amount').val();

                var allowance = $('#allowance').val();
                var overtime = $('#overtime_salary').val()
                var expenses = $('#expenses').val();
                var ticket = $('#ticket_amount').val();
                var leave_salary = $('#leave_salary').val();
                var extra_deductions = $('#extra_deductions').val()

                if(allowance>0){  }else{ allowance = 0; }
                if(expenses>0){  }else{ expenses = 0; }
                if(leave_salary>0){  }else{ leave_salary = 0; }
                if(ticket>0){  }else{ ticket = 0; }
                if(overtime>0){  }else{ overtime = 0; }
                if(extra_deductions>0){  }else{ extra_deductions = 0; }

                if(advance_amount>0){  }else{ advance_amount = 0; }
                if(loan_emi>0){  }else{ loan_emi = 0; }

                var payment_amount = parseInt(act_payment_amount)-parseInt(leave_amount);
                payment_amount = parseInt(payment_amount)+parseInt(allowance);
                payment_amount = parseInt(payment_amount)-parseInt(extra_deductions);
                var netsal = payment_amount;
                payment_amount = parseInt(payment_amount)+parseInt(expenses);
                payment_amount = parseInt(payment_amount)+parseInt(leave_salary);
                payment_amount = parseInt(payment_amount)+parseInt(ticket);
                payment_amount = parseInt(payment_amount)+parseInt(overtime);

                payment_amount = parseInt(payment_amount)-parseInt(advance_amount);
                payment_amount = parseInt(payment_amount)-parseInt(loan_emi);

                $('#payment_amount').val(payment_amount);
                $('#net_salary').val(netsal);
            }

            $('#advance_amount').keyup(function() {
                payment_amount_calc();
            });

            $('#loan_emi').keyup(function() {
                payment_amount_calc();
            });

            $('#allowance').keyup(function() {
                payment_amount_calc();
            });

            $('#extra_deductions').keyup(function() {
                payment_amount_calc();
            });

        });
    </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payment' && $_GET['type']=='hourly_payment'){ ?>
    <?php
    $payment_month = strtotime($_GET['pay_date']);
    $p_month = date('F Y',$payment_month);
//
    $result = $this->Payroll_model->total_hours_worked($_GET['employee_id'],$_GET['pay_date']);

    /* total work clock-in > clock-out  */
    /*$sql_tw = "SELECT * FROM hrm_attendance_time where `employee_id` = '".$_GET['emp_id']."' and attendance_date like '%".$_GET['pay_date']."%'";
    $results_tw = mysqli_query($db_connection, $sql_tw);*/
    $hrs_old_int1 = '';
    $Total = '';
    $Trest = '';
    $total_time_rs = '';
    $hrs_old_int_res1 = '';
    foreach ($result->result() as $hour_work){
        // total work
        $clock_in =  new DateTime($hour_work->clock_in);
        $clock_out =  new DateTime($hour_work->clock_out);
        $interval_late = $clock_in->diff($clock_out);
        $hours_r  = $interval_late->format('%h');
        $minutes_r = $interval_late->format('%i');
        $total_time = $hours_r .":".$minutes_r.":".'00';

        $str_time = $total_time;

        $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

        sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

        $hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

        $hrs_old_int1 += $hrs_old_seconds;

        $Total = gmdate("H", $hrs_old_int1);
    }

    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><strong>Payment For</strong> <?php echo $p_month;?></h4>
    </div>
    <div class="modal-body">
        <form class="m-b-1" action="<?php echo site_url("payroll/add_pay_hourly") ?>" method="post" name="pay_hourly" id="pay_hourly">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name"><?php echo $this->lang->line('xin_payroll_hourly_rate');?></label>
                        <input type="text" name="hourly_rate" class="form-control" value="<?php echo $hourly_rate;?>" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $user_id?>">
                        <input type="hidden" value="<?php echo $user_id;?>" name="u_id">
                        <input type="hidden" value="<?php echo $_GET['pay_date'];?>" name="pay_date" id="pay_date">
                        <label for="name"><?php echo $this->lang->line('xin_total_hours_worked');?></label>
                        <input type="text" name="total_hours_work" class="form-control" value="<?php echo $Total;?>" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" name="department_id" value="<?php echo $department_id;?>" />
                        <input type="hidden" name="company_id" value="<?php echo $company_id;?>" />
                        <input type="hidden" name="location_id" value="<?php echo $location_id;?>" />
                        <input type="hidden" name="designation_id" value="<?php echo $designation_id;?>" />
                        <label for="name"><?php echo $this->lang->line('xin_payroll_payment_amount');?></label>
                        <input type="text" name="payment_amount" class="form-control" value="<?php echo (int)$Total * (int)$hourly_rate;?>" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="payment_method"><?php echo $this->lang->line('xin_payment_method');?></label>
                        <select name="payment_method" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_payment_method');?>">
                            <option value="">&nbsp;</option>
<!--                            <option value="1">Online</option>-->
                            <option value="4">Bank Transfer</option>
                            <option value="5">Cheque</option>
                            <option value="6">Cash</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name"><?php echo $this->lang->line('xin_payment_comment');?></label>
                        <input type="text" class="form-control" name="comments">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_pay');?></button>
        </form>
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });

            // On page load: datatable
            var xin_table = $('#xin_table').dataTable({
                "bDestroy": true,
                "pageLength": 100,
                dom: 'Blfrtip',
                select: {
                    style: 'multi', // Allow multiple row selection
                    // selector: 'td:first-child' // Allow selection when clicking on the first column
                },
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: ':visible:not(.no-export)',
                            modifier: {
                                selected: true // Export selected rows only
                            }
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        exportOptions: {
                            columns: ':visible:not(.no-export)',
                            modifier: {
                                selected: true // Export selected rows only
                            }
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: ':visible:not(.no-export)',
                            modifier: {
                                selected: true // Export selected rows only
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: ':visible:not(.no-export)',
                            modifier: {
                                selected: true // Export selected rows only
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: ':visible:not(.no-export)',
                            modifier: {
                                selected: true // Export selected rows only
                            }
                        }
                    }
                ],

                "ajax": {
                    url : "<?php echo site_url("payroll/payslip_list") ?>?employee_id=0&month_year=<?php echo $this->input->get('pay_date');?>",
                    type : 'GET'
                },
                "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "columnDefs": [ {
                    "targets": 0,
                    "orderable": false
                } ,
                    {
                        "targets": 17, // Index of the column to hide
                        "visible": false
                    }
                ]
            });

            $("#pay_hourly").submit(function(e){

                /*Form Submit*/
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=12&data=hourly&add_type=pay_hourly&form="+action,
                    cache: false,
                    success: function (JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.emo_hourly_pay').modal('toggle');
                            xin_table.api().ajax.reload(function(){
                                toastr.success(JSON.result);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>
<?php }
?>
