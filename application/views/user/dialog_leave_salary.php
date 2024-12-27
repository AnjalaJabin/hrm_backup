<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['leave_id']) && $_GET['data']=='leave'){
    ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data">Edit Leave</h4>
    </div>
    <form class="m-b-1" action="<?php echo site_url("leave_salary/edit_leave").'/'.$leave_id; ?>/" method="post" name="edit_leave" id="edit_leave">
        <input type="hidden" name="_method" value="EDIT">
        <div class="modal-body">
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
                        <label for="amount">Amount</label>
                        <input class="form-control"  placeholder=" Amount" id="salary1" readonly name="salary" type="text" value="<?php echo "AED ".number_format($amount,2);?>">
                        <input hidden placeholder=" Amount" id="amount1"   name="amount" type="text" value="<?php echo $amount;?>">
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="start_date">Days</label>
                <input class="form-control" placeholder="Days" id="days1" name="days" type="text" value="<?php echo $leave_days;?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="start_date">Date</label>
                <input class="form-control e_date" placeholder="Date" readonly="true" name="date" type="text" value="<?php echo $paid_date;?>">
            </div>
        </div>

        </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#emp-select').on('change', function() {
                var employeeId = $(this).val();
                var days = $('#days1').val();
                $.ajax({
                    url : site_url+"leave_salary/check_salary/",
                    type: 'POST',
                    data: {employee_id: employeeId, days: days},
                    success: function(JSON) {
                        // Do something with the response, such as displaying the leave balance
                        $('#salary1').val("AED " + JSON.amount);
                        $('#amount1').val(JSON.amount);

                        toastr.warning(JSON.result);
                    },
                    error: function() {
                        alert('Error occurred while fetching amount');
                    }
                });
            });
     $('#days1').on('keyup', function() {
                    var employeeId = $('#emp-select').val();
                    var days = $(this).val();
         if((employeeId!='')&&(employeeId!=undefined)) {

             $.ajax({
                 url: site_url + "leave_salary/check_salary/",
                 type: 'POST',
                 data: {employee_id: employeeId, days: days},
                 success: function (JSON) {
                     // Do something with the response, such as displaying the leave balance
                     $('#salary1').val("AED " + JSON.amount);
                     $('#amount1').val(JSON.amount);
                     console.log("ksjj");
                     toastr.warning(JSON.result);
                 },
                 error: function () {
                     alert('Error occurred while fetching amount');
                 }
             });
         }
                });


            var xin_table = $('#xin_table').dataTable({
                "bDestroy": true,
                "ajax": {
                    url : site_url+"leave_salary/leave_salary_list/",
                    type : 'GET'
                },
                "order": [[6, "desc"]] ,

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
