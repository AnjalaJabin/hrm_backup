<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['termination_id']) && $_GET['data']=='termination'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Termination</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("termination/update").'/'.$termination_id; ?>" method="post" name="edit_termination" id="edit_termination">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $termination_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee Terminated</label>
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
              <label for="notice_date">Notice Date</label>
              <input class="form-control d_date" placeholder="Notice Date" readonly name="notice_date" type="text" value="<?php echo $notice_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="termination_date">Termination Date</label>
              <input class="form-control d_date" placeholder="Termination Date" readonly name="termination_date" type="text" value="<?php echo $termination_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="type">Termination Type</label>
              <select class="select2" data-plugin="select_hrm" data-placeholder="Select Termination Type..." name="type">
                <option value=""></option>
                <?php foreach($all_termination_types as $termination_type) {?>
                <option value="<?php echo $termination_type->termination_type_id?>" <?php if($termination_type->termination_type_id==$termination_type_id):?> selected="selected"<?php endif;?>><?php echo $termination_type->type;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="10" id="description2"><?php echo $description;?></textarea>
        </div>
      </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <div class="form-group">
          <label for="status">Status</label>
          <select name="status" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Select Status...">
            <option value="0" <?php if($status=='0'):?> selected <?php endif; ?>>Pending</option>
            <option value="1" <?php if($status=='1'):?> selected <?php endif; ?>>Accepted</option>
            <option value="2" <?php if($status=='2'):?> selected <?php endif; ?>>Rejected</option>
          </select>
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
            url : "<?php echo site_url("termination/termination_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		
		$('#description2').summernote({
		  height: 135,
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
		$("#edit_termination").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			var description = $("#description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=termination&form="+action+"&description="+description,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['termination_id']) && $_GET['data']=='view_termination'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Termination</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee Terminated</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($employee_id==$employee->user_id):?><?php echo $employee->first_name.' '.$employee->last_name;?><?php endif;?><?php } ?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="notice_date">Notice Date</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $notice_date;?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="termination_date">Termination Date</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $termination_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="type">Termination Type</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_termination_types as $termination_type) {?><?php if($termination_type_id==$termination_type->termination_type_id):?><?php echo $termination_type->type;?><?php endif;?><?php } ?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label>
          <?php echo html_entity_decode($description);?>
        </div>
      </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <div class="form-group">
          <label for="status">Status</label>
          <?php if($status=='0'): $t_status = 'Pending';?>  <?php endif; ?>
          <?php if($status=='1'): $t_status = 'Accepted';?>  <?php endif; ?>
          <?php if($status=='2'): $t_status = 'Rejected';?>  <?php endif; ?>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $t_status;?>">
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
