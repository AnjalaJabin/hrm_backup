<?php $result = $this->Xin_model->all_company_locations($company_id);?>
<?php
?>
<div class="form-group">
    <label for="designation">Transfer To (Location)</label>
    <select class="form-control" name="transfer_location" id="location_id">
        <option value="">Select One</option>
        <?php foreach($result as $location) {?>
        	<option value="<?php echo $location->location_id?>"><?php echo $location->location_name?></option>
        <?php } ?>
    </select>
</div>

<script type="text/javascript">
$(document).ready(function(){	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	// get departments
    jQuery("#location_id").change(function(){
    	jQuery.get(base_url+"/department/"+jQuery(this).val(), function(data, status){
    		jQuery('#department_ajax').html(data);
    	});
    });
});
</script>