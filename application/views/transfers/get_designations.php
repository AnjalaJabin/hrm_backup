<?php $result = $this->Xin_model->get_all_department_designations($department_id);?>
<?php
?>
<div class="form-group">
    <label for="designation">Designation</label>
    <select class="form-control" name="transfer_designation">
        <option value="">Select One</option>
        <?php foreach($result as $designation) {?>
        	<option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
        <?php } ?>
    </select>
</div>

<script type="text/javascript">
$(document).ready(function(){	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
});
</script>