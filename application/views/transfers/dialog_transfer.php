<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['transfer_id']) && $_GET['data']=='transfer'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Transfer</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("transfers/update").'/'.$transfer_id; ?>" method="post" name="edit_transfer" id="edit_transfer">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $transfer_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $employee_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee to Transfer</label>
          <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
            <option value=""></option>
            <?php foreach($all_employees as $employee) {?>
            <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$employee_id):?> selected="selected"<?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="transfer_date">Transfer Date</label>
              <input class="form-control edate" placeholder="Transfer Date" readonly name="transfer_date" type="text" value="<?php echo $transfer_date;?>">
            </div>
          </div>
        </div>
        
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="transfer_location">Transfer To (Company)</label>
                <select class="form-control" id="company_id" data-placeholder="Select Company..." name="transfer_company">
                  <option value="">Select One</option>
                  <?php foreach($all_companies as $company) {?>
                  <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$transfer_company):?> selected="selected"<?php endif;?>><?php echo $company->name;?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group" id="location_ajax">
                <label for="transfer_location">Transfer To (Location)</label>
                <select class="form-control" id="location_id" data-placeholder="Select Location..." name="transfer_location">
                  <option value="">Select One</option>
                  <?php foreach($all_locations as $location) {?>
                  <option value="<?php echo $location->location_id?>" <?php if($location->location_id==$transfer_location):?> selected="selected"<?php endif;?>><?php echo $location->location_name;?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="transfer_department">Transfer To (Department)</label>
              <select class="select2" data-plugin="select_hrm" data-placeholder="Select Department..." name="transfer_department">
            <option value=""></option>
            <?php foreach($all_departments as $department) {?>
            <option value="<?php echo $department->department_id?>" <?php if($department->department_id==$transfer_department):?> selected="selected"<?php endif;?>><?php echo $department->department_name;?></option>
            <?php } ?>
          </select>
            </div>
          </div>
            <div class="col-md-6">
              <div class="form-group" id="designation_ajax">
                <label for="transfer_department">Designation</label>
                <select class="form-control" id="designation_id" data-placeholder="Select Designation..." name="transfer_designation">
                  <option value="">Select One</option>
                    <?php foreach($all_designations as $designation) {?>
                    <option value="<?php echo $designation->designation_id?>" <?php if($designation->designation_id==$transfer_designation):?> selected="selected"<?php endif;?>><?php echo $designation->designation_name;?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
        </div>
        <div class="row">
      <div class="col-md-12">
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
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="10" id="description2"><?php echo $description;?></textarea>
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
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/ion.rangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script> 
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("transfers/transfer_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		
		$('#description2').summernote({
		  height: 140,
		  minHeight: null,
		  maxHeight: null,
		  focus: false
		});
		$('.note-children-container').hide();
		$('.edate').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:'yy-mm-dd',
		yearRange: '1900:' + (new Date().getFullYear() + 15),
		beforeShow: function(input) {
			$(input).datepicker("widget").show();
		}
		});

		/* Edit data */
		$("#edit_transfer").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			var description = $("#description2").code();
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=transfer&form="+action+"&description="+description,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['transfer_id']) && $_GET['data']=='view_transfer'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Transfer</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee to Transfer</label>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($employee_id==$employee->user_id):?><?php echo $employee->first_name.' '.$employee->last_name;?><?php endif;?><?php } ?>">
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="transfer_date">Transfer Date</label>
              <input class="form-control date2" readonly="readonly" style="border:0" type="text" value="<?php echo $transfer_date;?>">
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="transfer_location">Transfer To (Company)</label>
                <select readonly class="form-control" id="company_id" data-placeholder="Select Company..." name="transfer_company">
                  <?php foreach($all_companies as $company) {?>
                  <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$transfer_company):?> selected="selected"<?php endif;?>><?php echo $company->name;?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group" id="location_ajax">
                <label for="transfer_location">Transfer To (Location)</label>
                <select readonly class="form-control" id="location_id" data-placeholder="Select Location..." name="transfer_location">
                  <?php foreach($all_locations as $location) {?>
                  <option value="<?php echo $location->location_id?>" <?php if($location->location_id==$transfer_location):?> selected="selected"<?php endif;?>><?php echo $location->location_name;?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="transfer_department">Transfer To (Department)</label>
              <select readonly class="form-control" data-plugin="select_hrm" data-placeholder="Select Department..." name="transfer_department">
            <?php foreach($all_departments as $department) {?>
            <option value="<?php echo $department->department_id?>" <?php if($department->department_id==$transfer_department):?> selected="selected"<?php endif;?>><?php echo $department->department_name;?></option>
            <?php } ?>
          </select>
            </div>
          </div>
            <div class="col-md-6">
              <div class="form-group" id="designation_ajax">
                <label for="transfer_department">Designation</label>
                <select readonly class="form-control" id="designation_id" data-placeholder="Select Designation..." name="transfer_designation">
                    <?php foreach($all_designations as $designation) {?>
                    <option value="<?php echo $designation->designation_id?>" <?php if($designation->designation_id==$transfer_designation):?> selected="selected"<?php endif;?>><?php echo $designation->designation_name;?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
        </div>
        <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="status">Status</label>
          <?php if($status=='0'): $t_status = 'Pending';?> <?php endif; ?>
          <?php if($status=='1'): $t_status = 'Accepted';?> <?php endif; ?>
          <?php if($status=='2'): $t_status = 'Rejected';?> <?php endif; ?>
          <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $t_status;?>">
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
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form> 
<?php }
?>
