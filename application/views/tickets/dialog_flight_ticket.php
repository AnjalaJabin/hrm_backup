<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && $_GET['data']=='ticket'){
    ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data">Edit Ticket</h4>
    </div>
    <form class="m-b-1" action="<?php echo site_url("flights/update").'/'.$ticket_id; ?>" method="post" name="edit_ticket" id="edit_ticket">
        <input type="hidden" name="_method" value="EDIT">
        <input type="hidden" name="_token" value="<?php echo $ticket_id;?>">
        <div class="modal-body">
            <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employees">Ticket for Employee</label>
                                <select class="form-control" id="employee-select1" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee" >
                                    <option value=""></option>
                                    <?php foreach($all_employees as $employee) {
                                            if($employee->ticket_eligibilty		){?>
                                        <option value="<?php echo $employee->user_id?>" <?php if($employee->user_id==$employee_id):?> selected <?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                    <?php } }?>
                                </select>
                            </div>
                        </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="balance" class="control-label">Balance</label>
                        <input id="leave_balance1" class="form-control" placeholder="Ticket Balance" value="<?php echo $balance+1;?>"readonly name="balance" type="text" value="">

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="ticket_no" class="control-label">Ticket Number</label>

                        <input type="text" value="<?php echo $ticket_no;?>" class="form-control" placeholder="Ticket No"  name="ticket_no">
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="airlines" class="control-label">Airlines </label>

                        <input type="text" value="<?php echo $airlines;?>" class="form-control" placeholder="Airlines Name"  name="airlines">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ticket_date" class="control-label">Date</label>
                        <input class="form-control e_date" value="<?php echo $ticket_date;?>" placeholder="Date" readonly name="ticket_date" type="text" >

                    </div>
                    <div class="form-group">
                        <label for="ticket_date" class="control-label">Amount</label>
                        <input class="form-control" value="<?php echo $amount;?>" placeholder="Amount"  name="amount" type="text" >

                    </div>
                    <div class="form-group">
                        <label for="destination" class="control-label">Destination </label>

                        <input type="text" value="<?php echo $destination;?>"class="form-control" placeholder="Destination Name"  name="destination">
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Ticket Remarks</label>
                        <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="5" id="description"><?php echo $description;?></textarea>
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
            $('#employee-select1').on('change', function() {
                var employeeId = $(this).val();
                $.ajax({
                    url : base_url+"/check_ticket_balance/",
                    type: 'POST',
                    data: {employee_id: employeeId},
                    success: function(response) {
                        // Do something with the response, such as displaying the leave balance
                        var leaveBalance = parseFloat(response);
                        $('#leave_balance1').val(leaveBalance.toFixed(5));
                    },
                    error: function() {
                        alert('Error occurred while fetching leave balance');
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

                "ajax": {
                    url : "<?php echo site_url("flights/ticket_list") ?>",
                    type : 'GET'
                },
                "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });

            $('#description').summernote({
                height: 67,
                minHeight: null,
                maxHeight: null,
                focus: false
            });
            $('.note-children-container').hide();

            /* Edit data */
            $("#edit_ticket").submit(function(e){
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');
                $('.save').prop('disabled', true);
                var description = $("#description2").code();
                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=1&edit_type=ticket&form="+action+"&description="+description,
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
            $('.e_date').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat:'yy-mm-dd',
                yearRange: '1900:' + (new Date().getFullYear() + 15),
            });

        });
    </script>
<?php }
?>
