<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_POST['jd']) && isset($_POST['leave_id']) && $_POST['data']=='leave'){
    ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data">Edit Leave</h4>
    </div>
    <form class="m-b-1" action="<?php echo site_url("employee/annual_leave/edit_leave").'/'.$leave_id; ?>/" method="post" name="edit_leave" id="edit_leave">
        <input type="hidden" name="_method" value="EDIT">
        <div class="modal-body">
            <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input class="form-control e_date" placeholder="Start Date" readonly="true" name="start_date" type="text" value="<?php echo $from_date;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input class="form-control e_date" placeholder="End Date" readonly="true" name="end_date" type="text" value="<?php echo $to_date;?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employees" class="control-label">Leave for Employee</label>
                                <select class="form-control" name="employee_id" data-plugin="select_hrm" id="emp-select" data-placeholder="Employee">
                                    <option value=""></option>
                                    <?php foreach($all_employees as $employee) {?>
                                        <option value="<?php echo $employee->user_id?>" <?php if($employee->user_id==$employee_id):?> selected <?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="balance" class="control-label">Leave Balance</label>
                                <input id="leave_balance1" class="form-control " placeholder="Leave Balance" readonly name="balance"  type="text" value="">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label> <input  type="checkbox" <?php if($leave_salary)echo "checked";?> value="1" id="leave_salary" name="leave_salary">Generate Leave Salary</label>

                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Remarks</label>
                        <textarea class="form-control textarea" placeholder="Remarks" name="remarks" cols="30" rows="15" id="remarks2"><?php echo $remarks;?></textarea>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
<!--    <script type="text/javascript" src="--><?php //echo base_url();?><!--skin/vendor/jquery/jquery-3.2.1.min.js"></script>-->

    <script type="text/javascript">

        $(document).ready(function(){

            var employeeId = $('#emp-select').val();
            $.ajax({
                url: site_url + "employee/annual_leave/check_leave_balance/",
                type: 'POST',
                data: {employee_id: employeeId},
                success: function(response) {
                    // Do something with the response, such as displaying the leave balance
                    $('#leave_balance1').val(response);
                },
                error: function() {
                    alert('Error occurred while fetching leave balance');
                }
            });


            $('#emp-select').on('change', function() {
                var employeeId = $(this).val();
                $.ajax({
                    url : site_url+"employee/annual_leave/check_leave_balance/",
                    type: 'POST',
                    data: {employee_id: employeeId},
                    success: function(response) {
                        // Do something with the response, such as displaying the leave balance
                        $('#leave_balance1').val(response);
                    },
                    error: function() {
                        alert('Error occurred while fetching leave balance');
                    }
                });
            });

            var xin_table = $('#xin_table').dataTable({
                "bDestroy": true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "order": [[5, "desc"]] ,

                "ajax": {
                    url : site_url+"employee/annual_leave/leave_list/",
                    type : 'GET'
                },
                "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });
            $('#remarks2').summernote({
                height: 120,
                minHeight: null,
                maxHeight: null,
                focus: false
            });
            $('.note-children-container').hide();

            // Date
            $('.e_date').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat:'yy-mm-dd',
                yearRange: '1900:' + (new Date().getFullYear() + 15),
            });
            /* Edit*/
            $("#edit_leave").submit(function(e){
                var remarks = $("#remarks2").code();
                /*Form Submit*/
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');
                $('.save').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=2&edit_type=leave&form="+action+"&remarks="+remarks,
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
        });
    </script>
<?php } ?>
