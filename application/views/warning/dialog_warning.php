<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['warning_id']) && $_GET['data']=='warning'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Warning</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("warning/update").'/'.$warning_id; ?>" method="post" name="edit_warning" id="edit_warning">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $warning_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="warning_to">Warning To</label>
          <select name="warning_to" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
            <option value=""></option>
            <?php foreach($all_employees as $employee) {?>
            <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$warning_to):?> selected="selected"<?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="type">Type of Warning</label>
              <select class="select2" data-plugin="select_hrm" data-placeholder="Type of Warning" name="type">
                <option value=""></option>
                <?php foreach($all_warning_types as $warning_type) {?>
                <option value="<?php echo $warning_type->warning_type_id?>" <?php if($warning_type->warning_type_id==$warning_type_id):?> selected="selected"<?php endif;?>><?php echo $warning_type->type;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="subject">Subject</label>
              <input class="form-control" placeholder="Subject" name="subject" type="text" value="<?php echo $subject;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="warning_by">Warning By</label>
              <select name="warning_by" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                <option value=""></option>
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$warning_by):?> selected="selected"<?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="warning_date">Warning Date</label>
              <input class="form-control d_date" placeholder="Warning Date" readonly name="warning_date" type="text" value="<?php echo $warning_date;?>">
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
            url : "<?php echo site_url("warning/warning_list") ?>",
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
		$("#edit_warning").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			var description = $("#description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=warning&form="+action+"&description="+description,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['warning_id']) && $_GET['data']=='view_warning'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Warning</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="warning_to">Warning To</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($warning_to==$employee->user_id):?><?php echo $employee->first_name.' '.$employee->last_name;?><?php endif;?><?php } ?>">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="type">Type of Warning</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_warning_types as $warning_type) {?><?php if($warning_type_id==$warning_type->warning_type_id):?><?php echo $warning_type->type;?><?php endif;?><?php } ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="subject">Subject</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $subject;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="warning_by">Warning By</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($warning_by==$employee->user_id):?><?php echo $employee->first_name.' '.$employee->last_name;?><?php endif;?><?php } ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="warning_date">Warning Date</label>
              <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $warning_date;?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label><br />
          <?php echo html_entity_decode($description);?>
        </div>
      </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <div class="form-group">
          <label for="status">Status</label>
          <?php if($status=='0'): $w_status = 'Pending';?>  <?php endif; ?>
          <?php if($status=='1'): $w_status = 'Accepted';?>  <?php endif; ?>
          <?php if($status=='2'): $w_status = 'Rejected';?>  <?php endif; ?>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $w_status;?>">
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
