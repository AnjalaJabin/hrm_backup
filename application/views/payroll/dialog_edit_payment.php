<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['emp_id']) && $_GET['data']=='edit_payment' && $_GET['type']=='edit_payment'){ ?>
    <?php
    $grade_template = $this->Payroll_model->read_template_information($monthly_grade_id);
    $hourly_template = $this->Payroll_model->read_hourly_wage_information($hourly_grade_id);
    $payment_month = strtotime($payment_date);
    $p_month = date('F Y',$payment_month);
    $monthy =date('Y-m',$payment_month);
    $no_of_days =$this->Xin_model->get_number_of_days($monthy);
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
// get monthly installment
//$user = $this->Xin_model->read_user_info($_GET['employee_id']);
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><strong>Payment For</strong> <?php echo '<span class="text-success">('.$first_name.' '.$last_name.')</span> '.$p_month;?></h4>
    </div>
    <div class="modal-body">
        <form class="m-b-1" action="<?php echo site_url("payroll/edit_pay_monthly") ?>" method="post" name="edit_pay_monthly" id="edit_pay_monthly">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" value="<?php echo $basic_salary/30; ?>" id="edit_per_day_basic"/>
                        <label for="name">Basic Salary</label>
                        <input type="text" name="gross_salary" class="form-control" value="<?php echo $basic_salary;?>" readonly>
                        <input type="hidden" id="edit_emp_id" value="<?php echo $_GET['emp_id'];?>" name="emp_id">
                                        </div>
                </div>
            </div>
            <input type="hidden" name="working_hours" id="edit_working_hours" class="form-control" value="<?php echo $working_hours;?>">

            <?php if($overtime_rate!=''): ?>
                <input type="hidden" name="overtime_rate" id="edit_overtime_rate" value="<?php echo $overtime_rate;?>">
            <?php else:?>
                <input type="hidden" name="overtime_rate" id="edit_overtime_rate" class="form-control" value="0">
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
                <?php $total_allow= $house_rent_allowance + $medical_allowance + $travelling_allowance + $other_allowance+$telephone_allowance;?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Total Allowance</label>
                            <input type="text" name="total_allowances" class="form-control" value="<?php echo $total_allow;?>" readonly>
                            <input type="hidden" value="<?php echo ($basic_salary+$total_allow)/$no_of_days; ?>" id="edit_per_day_salary"/>

                        </div>
                    </div>
                </div>
            <?php else:?>
                <input type="hidden" name="total_allowances" class="form-control" value="0">
            <?php endif;?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Total Deduction</label>
                            <input type="text" id="edit_total_deduction" name="total_deductions" class="form-control" value="<?php echo $total_deductions;?>" readonly>
                        </div>
                    </div>
                </div>
<!--            --><?php //else:?>
<!--                <input type="hidden" name="total_deductions" class="form-control" value="0">-->
<!--            --><?php //endif;?>
            <?php if($total_expenses):?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Total Expenses</label>
                            <input type="text" id="edit_expenses" name="total_expenses" class="form-control" value="<?php echo round($total_expenses,2);?>" readonly>
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
                            <label for="name">Ticket Amoont</label>
                            <input type="text" id="edit_ticket_amount" name="ticket_amount" class="form-control" value="<?php echo round($ticket_amount,2);?>" readonly>
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
                            <input type="text" id="edit_leave_salary" name="leave_salary" class="form-control" value="<?php echo round($leave_salary,2);?>" readonly>
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
<!--            --><?php //if($advance_amount!=0 || $loan_emi!=0):?>
                <div class="row">
<!--                    --><?php //if($advance_amount!=0):?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Advance Deducted Salary</label>
                                <input type="text" class="form-control" name="advance_amount" id="edit_advance_amount" value="<?php echo $advance_amount;?>">
                            </div>
                        </div>
<!--                    --><?php //endif;?>
<!--                    --><?php //if($loan_emi!=0):?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Loan EMI</label>
                                <input type="text" class="form-control" name="loan_emi" id="edit_loan_emi" value="<?php echo $loan_emi;?>">
                            </div>
                        </div>
<!--                    --><?php //endif;?>
                </div>
<!--            --><?php //endif;?>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Leave Days</label>
                        <input type="text" class="form-control" name="leave_days" value="<?php echo $leave_days;?>" id="edit_leave_days">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Leave Deducted Salary</label>
                        <input type="text" class="form-control" name="leave_amount" value="<?php echo $leave_salary_deduct_amount;?>" readonly id="edit_leave_amount">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name"> Overtime Hours</label>
                        <input type="text" class="form-control" id="edit_overtime_hours" name="overtime_hours" value="<?php echo $overtime_hours;?>">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">Overtime Salary</label>
                        <input type="text" class="form-control" id="edit_overtime_salary"  name="overtime_salary" value="<?php echo $overtime_amount;?>" readonly >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Extra Allowance</label>
                        <input type="text" class="form-control" name="allowance" value="<?php echo $extra_allowance;?>" id="edit_allowance">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Extra Deductions</label>
                        <input type="text" class="form-control" name="extra_deductions" value="<?php echo $extra_deductions;?>" id="edit_extra_deductions">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Payment Amount</label>
                        <input type="text" name="payment_amount" class="form-control" value="<?php echo $net_salary;?>" readonly id="edit_payment_amount">
                        <input type="hidden" class="form-control" value="<?php echo $net_salary;?>" id="edit_act_payment_amount">
                        <input type="hidden" class="form-control" name="pay_id" value="<?php echo $pay_id;?>" id="edit_pay_id">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_method"><?php echo $this->lang->line('xin_payment_method');?></label>
                        <select name="payment_method" class="select2" data-plugin="select_hrm" data-placeholder="Chose Payment Method">
                            <option value="">&nbsp;</option>
<!--                            <option value="1" --><?php //if($payment_method==1) echo "selected"; ?><!-->Online</option>-->
<!--                            <option --><?php //if($payment_method==2) echo "selected"; ?><!-- value="2">PayPal</option>-->
<!--                            <option --><?php //if($payment_method==3) echo "selected"; ?><!-- value="3">Payoneer</option>-->
                            <option <?php if($payment_method==4) echo "selected"; ?> value="4">Bank Transfer</option>
                            <option <?php if($payment_method==5) echo "selected"; ?> value="5">Cheque</option>
                            <option <?php if($payment_method==6) echo "selected"; ?> value="6">Cash</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Comments</label>
                        <input type="text" class="form-control" name="comments" value="<?php echo $comments?>">
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
                    url : "<?php echo site_url("payroll/payslip_list") ?>?employee_id=0&month_year=<?php echo $payment_date;?>"+"&dept="+department_id,
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

            $("#edit_pay_monthly").submit(function(e){

                /*Form Submit*/
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=11&data=monthly&add_type=edit_monthly_payment&form="+action,
                    cache: false,
                    success: function (JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('.save').prop('disabled', false);
                        } else {
                            $('.edit-modal-data').modal('toggle');
                            xin_table.api().ajax.reload(function(){
                                toastr.success(JSON.result);
                            }, true);
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });

            $('#edit_leave_days').keyup(function() {
                var leave_days = $(this).val();
                var per_day_sal= $('#edit_per_day_salary').val();
                if(leave_days>0)
                {
                    var leave_amount = (parseFloat(leave_days)*parseFloat(per_day_sal)).toFixed(2);
                    $('#edit_leave_amount').val(leave_amount);
                    payment_amount_calc();
                    total_deduction_calc();

                }
                else
                {
                    $('#edit_leave_amount').val('0');
                    payment_amount_calc();
                    total_deduction_calc();

                }
            });
            $('#edit_overtime_hours').keyup(function() {
                var ot_hours = $(this).val();
                var ot_val = $('#edit_overtime_rate').val();
                var working_hours = $('#edit_working_hours').val();
                var per_day_sal= $('#edit_per_day_basic').val();
                console.log(ot_val);

                if((ot_hours>0)&&(ot_val>0)&&(ot_val!=undefined))
                {
                    var ot_amount = (parseFloat(ot_hours)*parseFloat(ot_val)*(parseFloat(per_day_sal)/parseFloat(working_hours))).toFixed(2);
                    $('#edit_overtime_salary').val(ot_amount);
                    payment_amount_calc();
                }
                else
                {
                    $('#edit_overtime_salary').val('0');
                    payment_amount_calc();
                }
            });
            $('#edit_loan_emi').keyup(function() {
                payment_amount_calc();
                total_deduction_calc();

            });
            $('#edit_advance_amount').keyup(function() {
                payment_amount_calc();
                total_deduction_calc();

            });
            $('#edit_extra_deductions').keyup(function() {
                payment_amount_calc();
                total_deduction_calc();

            });
            function total_deduction_calc(){
                var advance_amount = $('#edit_advance_amount').val();
                var loan_emi = $('#edit_loan_emi').val();

                var leave_amount = $('#edit_leave_amount').val();
                var extra_deductions = $('#edit_extra_deductions').val();
                if(extra_deductions>0){  }else{ extra_deductions = 0; }

                if(advance_amount>0){  }else{ advance_amount = 0; }
                if(loan_emi>0){  }else{ loan_emi = 0; }
                if(leave_amount>0){  }else{ leave_amount = 0; }

                var payment_amount = parseInt(leave_amount)+parseInt(advance_amount)+parseInt(loan_emi)+parseInt(extra_deductions);
                $('#edit_total_deduction').val(payment_amount);


            }

            function payment_amount_calc(){
                var advance_amount = $('#edit_advance_amount').val();
                var loan_emi = $('#edit_loan_emi').val();

                var leave_amount = $('#edit_leave_amount').val();
                var act_payment_amount = $('#edit_act_payment_amount').val();

                var allowance = $('#edit_allowance').val();
                var overtime = $('#edit_overtime_salary').val()
                var expenses = $('#edit_expenses').val();
                var leave_salary = $('#edit_leave_salary').val();
                var ticket_amount = $('#edit_ticket_amount').val();
                var extra_deductions = $('#edit_extra_deductions').val()

                if(allowance>0){  }else{ allowance = 0; }
                if(expenses>0){  }else{ expenses = 0; }
                if(leave_salary>0){  }else{ leave_salary = 0; }
                if(ticket_amount>0){  }else{ ticket_amount = 0; }
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
                payment_amount = parseInt(payment_amount)+parseInt(ticket_amount);
                payment_amount = parseInt(payment_amount)+parseInt(overtime);

                payment_amount = parseInt(payment_amount)-parseInt(advance_amount);
                payment_amount = parseInt(payment_amount)-parseInt(loan_emi);

                $('#edit_payment_amount').val(payment_amount);
                $('#edit_net_salary').val(netsal);
            }

            $('#edit_advance_amount').keyup(function() {
                payment_amount_calc();
            });

            $('#edit_loan_emi').keyup(function() {
                payment_amount_calc();
            });

            $('#edit_allowance').keyup(function() {
                payment_amount_calc();
            });

            $('#edit_extra_deductions').keyup(function() {
                payment_amount_calc();
            });

        });
    </script>
<?php }
?>
