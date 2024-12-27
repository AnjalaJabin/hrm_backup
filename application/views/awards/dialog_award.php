<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['award_id']) && $_GET['data']=='award'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Award</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("awards/update").'/'.$award_id; ?>" method="post" name="edit_award" id="edit_award">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $award_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $award_type_id;?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="employee">Employee</label>
          <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
            <option value=""></option>
            <?php foreach($all_employees as $employee) {?>
            <option value="<?php echo $employee->user_id;?>" <?php if($employee->user_id==$employee_id):?> selected <?php endif; ?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="award_type">Award Type</label>
              <select name="award_type_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose Award Type...">
                <option value=""></option>
                <?php foreach($all_award_types as $award_type) {?>
                <option value="<?php echo $award_type->award_type_id;?>" <?php if($award_type->award_type_id==$award_type_id):?> selected <?php endif; ?>><?php echo $award_type->award_type;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="award_date">Date</label>
              <input class="form-control d_award_date" placeholder="Award Date" readonly="true" name="award_date" type="text" value="<?php echo $created_at;?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="month_year">Month & Year</label>
              <input class="form-control d_month_year" placeholder="Month & Year of Award" readonly="true" name="month_year" type="text" value="<?php echo $award_month_year;?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="15" id="description2"><?php echo $description;?></textarea>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="gift">Gift</label>
          <input class="form-control" placeholder="Gift" name="gift" type="text" value="<?php echo $gift_item;?>">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="cash">Cash</label>
          <input class="form-control" placeholder="Cash" name="cash" type="text" value="<?php echo $cash_price;?>">
        </div>
      </div>
      <div class="col-md-3">
        <div class='form-group'>
          <div>
            <label for="photo">Award Photo</label>
          </div>
          <span class="btn btn-primary btn-file">
              Browse <input type="file" name="award_picture" id="award_picture">
            </span>
          <br>
          <small>Upload files only: gif,png,jpg,jpeg</small> </div>
      </div>
      <div class="col-md-3">
        <div class='form-group'>
          <?php if($award_photo!='' && $award_photo!='no file') {?>
          <br />
          <img src="<?php echo base_url().'uploads/award/'.$award_photo;?>" width="70px" id="u_file"><br />
          <a href="<?php echo site_url()?>download?type=award&filename=<?php echo $award_photo;?>">Download</a>
          <?php } else {?>
          <p>&nbsp;</p>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="award_information">Award Information</label>
      <textarea class="form-control" placeholder="Award Information" name="award_information" cols="30" rows="3" id="award_information"><?php echo $award_information;?></textarea>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Update</button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("awards/award_list") ?>",
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		
		$('#description2').summernote({
		  height: 151,
		  minHeight: null,
		  maxHeight: null,
		  focus: false
		});
		$('.note-children-container').hide();
		// Award Date
		$('.d_award_date').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:'yy-mm-dd',
		yearRange: '1900:' + (new Date().getFullYear() + 15),
		beforeShow: function(input) {
			$(input).datepicker("widget").show();
		}
		});
		// Award Month & Year
		$('.d_month_year').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat:'yy-mm',
		yearRange: '1900:' + (new Date().getFullYear() + 15),
		beforeShow: function(input) {
			$(input).datepicker("widget").addClass('hide-calendar');
		},
		onClose: function(dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
			$(this).datepicker('widget').removeClass('hide-calendar');
			$(this).datepicker('widget').hide();
		}
			
		});
		
		$("#edit_award").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		var description = $("#description2").code();
		fd.append("is_ajax", 1);
		fd.append("edit_type", 'award');
		fd.append("description", description);
		fd.append("form", action);
		e.preventDefault();
		$('.icon-spinner3').show();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
						$('.save').prop('disabled', false);
						$('.icon-spinner3').hide();
				} else {
					xin_table.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					$('.edit-modal-data').modal('toggle');
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	});	
  </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['award_id']) && $_GET['data']=='view_award'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Award</h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="employee">Employee</label>
        <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_employees as $employee) {?><?php if($employee_id==$employee->user_id):?><?php echo $employee->first_name.' '.$employee->last_name;?><?php endif;?><?php } ?>">
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="award_type">Award Type</label>
            <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php foreach($all_award_types as $award_type) {?><?php if($award_type_id==$award_type->award_type_id):?><?php echo $award_type->award_type;?><?php endif;?><?php } ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="award_date">Date</label>
            <input class="form-control d_award_date" readonly="readonly" style="border:0" type="text" value="<?php echo $created_at;?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="month_year">Month & Year</label>
            <input class="form-control d_month_year" readonly="readonly" style="border:0" type="text" value="<?php echo $award_month_year;?>">
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="description">Description</label>
        <br />
        <?php echo html_entity_decode($description);?> </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <label for="gift">Gift</label>
        <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $gift_item;?>">
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="cash">Cash</label>
        <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo $cash_price;?>">
      </div>
    </div>
    <div class="col-md-5">
      <div class="form-group">
        <?php if($award_photo!='' && $award_photo!='no file') {?>
        <br />
        <img src="<?php echo base_url().'uploads/award/'.$award_photo;?>" width="70px" id="u_file"><br />
        <a href="<?php echo site_url()?>download?type=award&filename=<?php echo $award_photo;?>">Download</a>
        <?php } else {?>
        <p>&nbsp;</p>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="award_information">Award Information</label>
    <br />
    <input class="form-control" readonly="readonly" style="border:0" type="text" value="<?php echo html_entity_decode($award_information);?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>
<?php }
?>
