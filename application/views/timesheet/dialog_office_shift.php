<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['office_shift_id']) && $_GET['data']=='shift'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Office Shift</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("timesheet/edit_office_shift").'/'.$office_shift_id; ?>/" method="post" name="edit_office_shift" id="edit_office_shift">
  <input type="hidden" name="_method" value="EDIT">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="name">Shift Name</label>
          <input class="form-control" placeholder="Shift Name" name="shift_name" type="text" value="<?php echo $shift_name;?>">
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2">Monday</label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-1" placeholder="In Time" readonly="1" name="monday_in_time" type="text" value="<?php echo $monday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-1" placeholder="Out Time" readonly="1" name="monday_out_time" type="text" value="<?php echo $monday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-primary clear-time" data-clear-id="1">Clear</button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2">Tuesday</label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-2" placeholder="In Time" readonly="1" name="tuesday_in_time" type="text" value="<?php echo $tuesday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-2" placeholder="Out Time" readonly="1" name="tuesday_out_time" type="text" value="<?php echo $tuesday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-primary clear-time" data-clear-id="2">Clear</button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2">Wednesday</label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-3" placeholder="In Time" readonly="1" name="wednesday_in_time" type="text" value="<?php echo $wednesday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-3" placeholder="Out Time" readonly="1" name="wednesday_out_time" type="text" value="<?php echo $wednesday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-primary clear-time" data-clear-id="3">Clear</button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2">Thursday</label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-4" placeholder="In Time" readonly="1" name="thursday_in_time" type="text" value="<?php echo $thursday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-4" placeholder="Out Time" readonly="1" name="thursday_out_time" type="text" value="<?php echo $thursday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-primary clear-time" data-clear-id="4">Clear</button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2">Friday</label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-5" placeholder="In Time" readonly="1" name="friday_in_time" type="text" value="<?php echo $friday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-5" placeholder="Out Time" readonly="1" name="friday_out_time" type="text" value="<?php echo $friday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-primary clear-time" data-clear-id="5">Clear</button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2">Saturday</label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-6" placeholder="In Time" readonly="1" name="saturday_in_time" type="text" value="<?php echo $saturday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-6" placeholder="Out Time" readonly="1" name="saturday_out_time" type="text" value="<?php echo $saturday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-primary clear-time" data-clear-id="6">Clear</button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2">Sunday</label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-7" placeholder="In Time" readonly="1" name="sunday_in_time" type="text" value="<?php echo $sunday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-7" placeholder="Out Time" readonly="1" name="sunday_out_time" type="text" value="<?php echo $sunday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-primary clear-time" data-clear-id="7">Clear</button>
          </div>
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
							
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url : "<?php echo site_url("timesheet/office_shift_list") ?>",
			type : 'GET'
		},
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
	});
	
	// Clock
	$('.clockpicker').clockpicker();
	var input = $('.timepicker').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});
	
	/* Edit data */
	$("#edit_office_shift").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&edit_type=shift&form="+action,
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
	$(".clear-time").click(function(){
		var clear_id  = $(this).data('clear-id');
		$(".clear-"+clear_id).val('');
	});
});	
</script>
<?php } ?>
