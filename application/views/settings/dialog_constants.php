<?php
if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_document_type' && $_GET['type']=='ed_document_type'){
$row = $this->Xin_model->read_document_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Document Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_document_type") ?>/<?php echo $row[0]->document_type_id;?>/" method="post" name="ed_document_type_info" id="ed_document_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->document_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->document_type;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Document Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Document Type" value="<?php echo $row[0]->document_type;?>">
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
	var xin_table_document_type = $('#xin_table_document_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/document_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});	 

	/* Edit data */
	$("#ed_document_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=21&type=edit_record&data=ed_document_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_document_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_contract_type' && $_GET['type']=='ed_contract_type'){
$row = $this->Xin_model->read_contract_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Contract Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_contract_type") ?>/<?php echo $row[0]->contract_type_id;?>/" method="post" name="ed_contract_type_info" id="ed_contract_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->contract_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Contract Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Contract Type" value="<?php echo $row[0]->name?>">
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
	var xin_table_contract_type = $('#xin_table_contract_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/contract_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});	 

	/* Edit data */
	$("#ed_contract_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=22&type=edit_record&data=ed_contract_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_contract_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_payment_method' && $_GET['type']=='ed_payment_method'){
$row = $this->Xin_model->read_payment_method($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Payment Method</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_payment_method") ?>/<?php echo $row[0]->payment_method_id;?>/" method="post" name="ed_payment_method_info" id="ed_payment_method_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->payment_method_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->method_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Payment Method:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Payment Method" value="<?php echo $row[0]->method_name;?>">
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
	var xin_table_payment_method = $('#xin_table_payment_method').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/payment_method_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});	 

	/* Edit data */
	$("#ed_payment_method_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=23&type=edit_record&data=ed_payment_method_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_payment_method.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_education_level' && $_GET['type']=='ed_education_level'){
$row = $this->Xin_model->read_education_level($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Education Level</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_education_level") ?>/<?php echo $row[0]->education_level_id;?>/" method="post" name="ed_education_level_info" id="ed_education_level_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->education_level_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Education Level:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Education Level" value="<?php echo $row[0]->name;?>">
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
	var xin_table_education_level = $('#xin_table_education_level').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/education_level_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});	 

	/* Edit data */
	$("#ed_education_level_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=24&type=edit_record&data=ed_education_level_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_education_level.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_qualification_language' && $_GET['type']=='ed_qualification_language'){
$row = $this->Xin_model->read_qualification_language($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Language</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_qualification_language") ?>/<?php echo $row[0]->language_id;?>/" method="post" name="ed_qualification_language_info" id="ed_qualification_language_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->language_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Language:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Language" value="<?php echo $row[0]->name;?>">
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
	var xin_table_qualification_language = $('#xin_table_qualification_language').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/qualification_language_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});	 

	/* Edit data */
	$("#ed_qualification_language_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=25&type=edit_record&data=ed_qualification_language_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_qualification_language.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_qualification_skill' && $_GET['type']=='ed_qualification_skill'){
$row = $this->Xin_model->read_qualification_skill($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Skill</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_qualification_skill") ?>/<?php echo $row[0]->skill_id;?>/" method="post" name="ed_qualification_skill_info" id="ed_qualification_skill_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->skill_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Skill:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Skill" value="<?php echo $row[0]->name;?>">
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
	var xin_table_qualification_skill = $('#xin_table_qualification_skill').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/qualification_skill_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});	 

	/* Edit data */
	$("#ed_qualification_skill_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=26&type=edit_record&data=ed_qualification_skill_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_qualification_skill.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_award_type' && $_GET['type']=='ed_award_type'){
$row = $this->Xin_model->read_award_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Award Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_award_type") ?>/<?php echo $row[0]->award_type_id;?>/" method="post" name="ed_award_type_info" id="ed_award_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->award_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->award_type;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Award Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Award Type" value="<?php echo $row[0]->award_type;?>">
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
	var xin_table_award_type = $('#xin_table_award_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/award_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	}); 

	/* Edit data */
	$("#ed_award_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=38&type=edit_record&data=ed_award_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_award_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_leave_type' && $_GET['type']=='ed_leave_type'){
$row = $this->Xin_model->read_leave_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Leave Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_leave_type") ?>/<?php echo $row[0]->leave_type_id;?>/" method="post" name="ed_leave_type_info" id="ed_leave_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->leave_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type_name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Leave Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Leave Type" value="<?php echo $row[0]->type_name;?>">
      </div>
      <div class="form-group">
        <label for="days_per_year" class="form-control-label">Days Per Year:</label>
        <input type="text" class="form-control" name="days_per_year" placeholder="Enter Days Per Year" value="<?php echo $row[0]->days_per_year?>">
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
	var xin_table_leave_type = $('#xin_table_leave_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/leave_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});

	/* Edit data */
	$("#ed_leave_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=39&type=edit_record&data=ed_leave_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_leave_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_warning_type' && $_GET['type']=='ed_warning_type'){
$row = $this->Xin_model->read_warning_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Warning Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_warning_type") ?>/<?php echo $row[0]->warning_type_id;?>/" method="post" name="ed_warning_type_info" id="ed_warning_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->warning_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Warning Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Warning Type" value="<?php echo $row[0]->type;?>">
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
	var xin_table_warning_type = $('#xin_table_warning_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/warning_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	}); 

	/* Edit data */
	$("#ed_warning_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=40&type=edit_record&data=ed_warning_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_warning_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_termination_type' && $_GET['type']=='ed_termination_type'){
$row = $this->Xin_model->read_termination_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Termination Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_termination_type") ?>/<?php echo $row[0]->termination_type_id;?>/" method="post" name="ed_termination_type_info" id="ed_termination_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->termination_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Termination Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Termination Type" value="<?php echo $row[0]->type;?>">
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
	var xin_table_termination_type = $('#xin_table_termination_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/termination_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	}); 

	/* Edit data */
	$("#ed_termination_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=41&type=edit_record&data=ed_termination_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_termination_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_expense_type' && $_GET['type']=='ed_expense_type'){
$row = $this->Xin_model->read_expense_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Expense Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_expense_type") ?>/<?php echo $row[0]->expense_type_id;?>/" method="post" name="ed_expense_type_info" id="ed_expense_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->expense_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Expense Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Expense Type" value="<?php echo $row[0]->name;?>">
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
	var xin_table_expense_type = $('#xin_table_expense_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/expense_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});

	/* Edit data */
	$("#ed_expense_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=42&type=edit_record&data=ed_expense_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_expense_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_job_type' && $_GET['type']=='ed_job_type'){
$row = $this->Xin_model->read_job_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Job Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_job_type") ?>/<?php echo $row[0]->job_type_id;?>/" method="post" name="ed_job_type_info" id="ed_job_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->job_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Job Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Job Type" value="<?php echo $row[0]->type;?>">
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
	var xin_table_job_type = $('#xin_table_job_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/job_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});

	/* Edit data */
	$("#ed_job_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=43&type=edit_record&data=ed_job_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_job_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_exit_type' && $_GET['type']=='ed_exit_type'){
$row = $this->Xin_model->read_exit_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Employee Exit Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_exit_type") ?>/<?php echo $row[0]->exit_type_id;?>/" method="post" name="ed_exit_type_info" id="ed_exit_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->exit_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Employee Exit Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Employee Exit Type" value="<?php echo $row[0]->type;?>">
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
	var xin_table_exit_type = $('#xin_table_exit_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/exit_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});

	/* Edit data */
	$("#ed_exit_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=44&type=edit_record&data=ed_exit_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_exit_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_travel_arr_type' && $_GET['type']=='ed_travel_arr_type'){
$row = $this->Xin_model->read_travel_arr_type($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Travel Arrangement Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_travel_arr_type") ?>/<?php echo $row[0]->arrangement_type_id;?>/" method="post" name="ed_travel_arr_type_info" id="ed_travel_arr_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->arrangement_type_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->type;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name" class="form-control-label">Travel Arrangement Type:</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Travel Arrangement Type" value="<?php echo $row[0]->type;?>">
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
	var xin_table_travel_arr_type = $('#xin_table_travel_arr_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/travel_arr_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});

	/* Edit data */
	$("#ed_travel_arr_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=46&type=edit_record&data=ed_travel_arr_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_travel_arr_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_currency_type' && $_GET['type']=='ed_currency_type'){
$row = $this->Xin_model->read_currency_types($_GET['field_id']);
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Currency Type</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("settings/update_currency_type") ?>/<?php echo $row[0]->currency_id;?>/" method="post" name="ed_currency_type_info" id="ed_currency_type_info">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $row[0]->currency_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $row[0]->name;?>">
  <div class="modal-body">
      <div class="form-group">
        <label for="name">Currency Name</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Currency Name" value="<?php echo $row[0]->name;?>">
      </div>
      <div class="form-group">
        <label for="name">Currency Code</label>
        <input type="text" class="form-control" name="code" placeholder="Enter Currency Code" value="<?php echo $row[0]->code;?>">
      </div>
      <div class="form-group">
        <label for="name">Currency Symbol</label>
        <input type="text" class="form-control" name="symbol" placeholder="Enter Currency Symbol" value="<?php echo $row[0]->symbol;?>">
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
	var xin_table_currency_type = $('#xin_table_currency_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 30, 50, 100, -1], [5, 10, 30, 50, 100, "All"]],
		"ajax": {
            url : "<?php echo site_url("settings/currency_type_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}			
	});

	/* Edit data */
	$("#ed_currency_type_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=46&type=edit_record&data=ed_currency_type_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} else {
					$('.edit_setting_datail').modal('toggle');
					xin_table_currency_type.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['user_id']) && $_GET['data']=='password' && $_GET['type']=='password'){?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Change Password</h4>
</div>
<form id="profile_password" action="<?php echo site_url("employees/change_password");?>" name="e_change_password" method="post">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'];?>">
  <div class="modal-body">
      <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="new_password">Enter New Password</label>
          <input class="form-control" placeholder="Enter New Password" name="new_password" type="password">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="new_password_confirm" class="control-label">Enter New Confirm Password</label>
          <input class="form-control" placeholder="Enter New Confirm Password" name="new_password_confirm" type="password">
        </div>
      </div>
    </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Update</button>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	/* change password */
	jQuery("#profile_password").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=31&data=e_change_password&type=change_password&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					$('.pro_change_password').modal('toggle');
					toastr.success(JSON.result);
					jQuery('#profile_password')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['p']) && $_GET['data']=='policy' && $_GET['type']=='policy'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Company Policy</h4>
</div>
  <div class="modal-body">
      <div class="form-group">
        <div id="accordion" role="tablist" aria-multiselectable="true">
        <?php foreach($this->Xin_model->all_policies() as $_policy):?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $_policy->policy_id;?>" aria-expanded="true" aria-controls="collapseOne">
                         <?php
						 if($_policy->company_id==0){
							 $cname = 'All Companies';
						 } else {
							$company = $this->Xin_model->read_company_info($_policy->company_id);
							$cname = $company[0]->name;
						 }
						 ?>
                            <?php echo $_policy->title;?> (<?php echo $cname;?>)
                        </a>
                    </h4>
                </div>
                <div id="collapse<?php echo $_policy->policy_id;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                   <?php echo html_entity_decode($_policy->description);?>
                </div>
            </div>
           <?php endforeach;?> 
        </div>
      </div>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
<?php }
?>