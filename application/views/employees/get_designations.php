<?php $result = $this->Designation_model->ajax_designation_information($department_id);?>
<?php
?>
<div class="form-group">
    <label for="designation"><?php echo $this->lang->line('xin_designation');?></label>
    <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_designation');?>">
        <option value=""></option>
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