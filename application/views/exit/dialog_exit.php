<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['exit_id']) && $_GET['data']=='exit'){
    ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data">Edit Employee Exit</h4>
    </div>
    <form class="m-b-1" action="<?php echo site_url("endofservice/update").'/'.$exit_id; ?>" method="post" name="edit_exit" id="edit_exit">
        <input type="hidden" name="_method" value="EDIT">
        <input type="hidden" name="_token" value="<?php echo $exit_id;?>">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employee">Employee to Exit</label>
                        <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                            <option value=""></option>
                            <?php foreach($all_employees as $employee) {?>
                                <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$employee_id):?> selected="selected"<?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Exit Date</label>
                                <input class="form-control d_date" placeholder="Exit Date" readonly name="exit_date" type="text" value="<?php echo $exit_date;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Notice Date</label>
                                <input class="form-control d_date" placeholder="Noticd Date" readonly name="notice_date" type="text" value="<?php echo $notice_date;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type of Exit</label>
                                <select class="select2" data-plugin="select_hrm" data-placeholder="Type of Exit" name="type">
                                    <option value=""></option>
                                    <?php foreach($all_exit_types as $exit_type) {?>
                                        <option value="<?php echo $exit_type->exit_type_id?>" <?php if($exit_type->exit_type_id==$exit_type_id):?> selected="selected"<?php endif;?>><?php echo $exit_type->type;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_interview">Assets Returned</label>
                                <select class="select2" data-plugin="select_hrm" data-placeholder="Conducted Exit Interview<" name="assets_returned">
                                    <option value="1" <?php if(1==$assets_returned):?> selected="selected"<?php endif;?>>Yes</option>
                                    <option value="0" <?php if(0==$assets_returned):?> selected="selected"<?php endif;?>>No</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_inactivate_account">Net Amount</label>
                                <input class="form-control" name="net_amount" value="<?php echo $net_amount;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_inactivate_account">Gratuity Amount</label>
                                <input class="form-control" name="gratuity" value="<?php echo $gratuity;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_inactivate_account">Leave Salary</label>
                                <input class="form-control" name="leave_salary" value="<?php echo $leave_salary;?>">
                            </div>
                        </div> <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_inactivate_account">Expenses</label>
                                <input class="form-control" name="expenses" value="<?php echo $expenses;?>">
                            </div>
                        </div><div class="col-md-6">
                            <div class="form-group">
                                <label for="is_inactivate_account">Pending Salary</label>
                                <input class="form-control" name="pending_salary" value="<?php echo $pending_salary;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_inactivate_account">Overtime Amount</label>
                                <input class="form-control" name="overtime_amount" value="<?php echo $overtime_amount;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_inactivate_account">Loan Amount</label>
                                <input class="form-control" name="loan" value="<?php echo $loan;?>">
                            </div>
                        </div><div class="col-md-6">
                            <div class="form-group">
                                <label for="is_inactivate_account">Other Deductions</label>
                                <input class="form-control" name="other_deductions" value="<?php echo $other_deductions;?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control textarea" placeholder="Reason" name="reason" cols="30" rows="10" id="reason2"><?php echo $reason;?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="is_inactivate_account">Leave Balance</label>
                        <input class="form-control" name="leave_balance" value="<?php echo $leave_balance;?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="is_inactivate_account">Ticket  Balance</label>
                        <input class="form-control" name="ticket_balance" value="<?php echo $ticket_balance;?>">
                    </div>
                </div><div class="col-md-6">
                    <div class="form-group">
                        <label for="is_inactivate_account">Ticket  Amount</label>
                        <input class="form-control" name="ticket_amount" value="<?php echo $ticket_amount;?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
    <script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            // On page load: datatable
            var xin_table = $('#xin_table').dataTable({
                "bDestroy": true,
                "ajax": {
                    url : "<?php echo site_url("endofservice/exit_list") ?>",
                    type : 'GET'
                },
                "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });

            $('#reason2').summernote({
                height: 120,
                minHeight: null,
                maxHeight: null,
                focus: false
            });
            $('.note-children-container').hide();
            $('.d_date').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat:'yy-mm-dd',
                yearRange: '1900:' + (new Date().getFullYear() + 10),
                beforeShow: function(input) {
                    $(input).datepicker("widget").show();
                }
            });

            /* Edit data */
            $("#edit_exit").submit(function(e){
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');
                $('.save').prop('disabled', true);
                var reason = $("#reason2").code();
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=1&edit_type=exit&form="+action+"&reason="+reason,
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
    </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['exit_id']) && $_GET['data']=='view_exit'){
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data">View Employee Exit</h4>
    </div>
    <form class="m-b-1">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="employee">Employee to Exit</label>
                        <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($employee_id==$employee->user_id):?><?php echo $employee->first_name.' '.$employee->last_name;?><?php endif;?><?php } ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Exit Date</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $exit_date;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Notice Date</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $notice_date;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type of Exit</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_exit_types as $exit_type) {?><?php if($exit_type_id==$exit_type->exit_type_id):?><?php echo $exit_type->type;?><?php endif;?><?php } ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Net Amount</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $net_amount;?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Leave  Salary</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $leave_salary;?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Loan Amount</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $loan;?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Expenses</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $expenses;?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Other Deductions</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $other_deductions;?>">
                            </div>

                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_interview">Assets Returned</label>
                                <?php if($assets_returned=='1'): $interview = 'Yes';?>  <?php endif; ?>
                                <?php if($assets_returned=='0'): $interview = 'No';?>  <?php endif; ?>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $interview;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Pending  Salary</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $pending_salary;?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Gratuity Amount</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $gratuity;?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exit_date">Overtime Amount</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $overtime_amount;?>">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exit_date">Ticket Balance</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $ticket_balance;?>">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exit_date">Ticket Amount</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $ticket_amount;?>">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exit_date">Leave Balance</label>
                                <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $leave_balance;?>">
                            </div>

                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label><br />
                            <?php echo html_entity_decode($reason);?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </form>
<?php }
?>
