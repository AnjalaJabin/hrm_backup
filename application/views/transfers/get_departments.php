<?php $result = $this->Department_model->ajax_department_information($location_id);?>
<?php
?>
<div class="form-group">
    <label for="designation">Transfer To <?php echo $this->lang->line('xin_department');?></label>
    <select class="form-control" name="transfer_department" id="department_id">
        <option value="">Select One</option>
        <?php foreach($result as $department) {?>
        	<option value="<?php echo $department->department_id?>"><?php echo $department->department_name?></option>
        <?php } ?>
    </select>
</div>
<?php
//}
?>
<script type="text/javascript">
$(document).ready(function(){	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	// get designations
    jQuery("#department_id").change(function(){
    	jQuery.get(base_url+"/designation/"+jQuery(this).val(), function(data, status){
    		jQuery('#designation_ajax').html(data);
    	});
    });
});
</script>