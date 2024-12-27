<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['leave_id']) && $_GET['data']=='leave'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Leave</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_leave").'/'.$leave_id; ?>/" method="post" name="edit_leave" id="edit_leave">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="leave_type" class="control-label">Leave Type</label>
          <select class="form-control" name="leave_type" data-plugin="select_hrm" data-placeholder="Leave Type">
            <option value=""></option>
            <?php foreach($all_leave_types as $type) {?>
            <option value="<?php echo $type->leave_type_id?>" <?php if($type->leave_type_id==$leave_type_id):?> selected <?php endif;?>> <?php echo $type->type_name;?></option>
            <?php } ?>
          </select>
        </div>
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
          <div class="col-md-12">
            <div class="form-group">
              <label for="employees" class="control-label">Leave for Employee</label>
              <select class="form-control" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee">
                <option value=""></option>
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id?>" <?php if($employee->user_id==$employee_id):?> selected <?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                <?php } ?>
              </select>
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
    <div class="form-group">
      <label for="reason">Reason</label>
      <textarea class="form-control" placeholder="Leave Reason" name="reason" cols="30" rows="3" id="reason"><?php echo $reason;?></textarea>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save</button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
							
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url : "<?php echo site_url("timesheet/leave_list") ?>",
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
