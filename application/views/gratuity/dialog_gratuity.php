<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['ticket_id']) && $_GET['data']=='ticket'){
    ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data">Edit Gratuity Encashment</h4>
    </div>
    <form class="m-b-1" action="<?php echo site_url("gratuity/update").'/'.$id; ?>" method="post" name="edit_ticket" id="edit_gratuity">
        <input type="hidden" name="_method" value="EDIT">
        <input type="hidden" name="_token" value="<?php echo $id;?>">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employees">Select Employee</label>
                        <select class="form-control" id="employee-select1" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee" >
                            <option value=""></option>
                            <?php foreach($all_employees as $employee) {
                                if($employee->gratuity_eligibilty	){?>
                                    ?>
                                    <option value="<?php echo $employee->user_id?>" <?php if($employee->user_id==$employee_id):?> selected <?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                <?php }} ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="balance" class="control-label">Gratuity Balance</label>
                        <input id="leave_balance1" class="form-control " placeholder="Gratuity Balance" readonly  type="text" value="<?php echo "AED ".$balance;?>">
                        <input id="gratuity_balance1"hidden class="form-control " placeholder="Gratuity Balance" readonly name="balance" type="text" value="<?php echo  $balance;?>">

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="balance" class="control-label">Loan Balance</label>
                        <input id="loan_balance1" class="form-control " placeholder="Loan Balance" readonly  type="text" value="<?php echo '';?>">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="ticket_no" class="control-label">Amount</label>

                        <input type="text" class="form-control" placeholder="Amount"  value="<?php echo $amount; ?>"  name="amount">
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="date" class="control-label">Date</label>
                        <input class="form-control e_date" id="grat_date" placeholder="Date" readonly name="date" type="text" value="<?php echo $paid_date; ?>">

                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description"> Remarks</label>
                        <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="5" id="description2"><?php echo $description;?></textarea>
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
    <link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css">
    <script type="text/javascript" src="<?php echo base_url();?>skin/vendor/ion.rangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var employeeId = $('#employee-select1').val();
            var date = '';
            $.ajax({
                url : base_url+"/check_gratuity_balance/",
                type: 'POST',
                data: {employee_id: employeeId,date:date},
                success: function(response) {
                    // Do something with the response, such as displaying the leave balance
                    $('#leave_balance1').val("AED "+ response);
                    $('#gratuity_balance1').val(response);
                },
                error: function() {
                    alert('Error occurred while fetching gratuity balance');
                }
            });
            $.ajax({
                url : base_url+"/check_loan_balance/",
                type: 'POST',
                data: {employee_id: employeeId},
                success: function(response) {
                    // Do something with the response, such as displaying the leave balance
                    $('#loan_balance1').val("AED "+ response);
                },
                error: function() {
                    toastr.error('Error occurred while fetching loan balance');
                }
            });

            $('.e_date').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat:'yy-mm-dd',
                yearRange: '1900:' + (new Date().getFullYear() + 15),
            });

            $('#employee-select1').on('change', function() {
                var employeeId = $(this).val();
                var date = $('#grat_date').val();
                $.ajax({
                    url : base_url+"/check_gratuity_balance/",
                    type: 'POST',
                    data: {employee_id: employeeId,date:date},
                    success: function(response) {
                        // Do something with the response, such as displaying the leave balance
                        $('#leave_balance1').val("AED "+ response);
                        $('#gratuity_balance1').val(response);
                    },
                    error: function() {
                        alert('Error occurred while fetching gratuity balance');
                    }
                });
                $.ajax({
                    url : base_url+"/check_loan_balance/",
                    type: 'POST',
                    data: {employee_id: employeeId},
                    success: function(response) {
                        // Do something with the response, such as displaying the leave balance
                        $('#loan_balance1').val("AED "+ response);
                    },
                    error: function() {
                        toastr.error('Error occurred while fetching loan balance');
                    }
                });

            });

            // On page load: datatable
            var xin_table = $('#xin_table').dataTable({
                "bDestroy": true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "order": [[1, "asc"]] ,

                "ajax": {
                    url : "<?php echo site_url("gratuity/gratuity_list") ?>",
                    type : 'GET'
                },
                "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });

            $('#description2').summernote({
                height: 67,
                minHeight: null,
                maxHeight: null,
                focus: false
            });
            $('.note-children-container').hide();

            /* Edit data */
            $("#edit_gratuity").submit(function(e){
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');
                $('.save').prop('disabled', true);
                var description = $("#description2").val();
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=1&edit_type=gratuity&form="+action+"&description="+description,
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
<?php }
?>
