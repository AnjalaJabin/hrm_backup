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


    <?php
    if($result)
    {
    ?>
        <a class="btn btn-sm btn-primary pull-right text-white" id="des_add_btn"><i class="fa fa-plus icon"></i>New</a>
        <div style="background:#ddd; padding:7px; display:none;" id="des_add_div">
            <div class="input-group">
               <input type="text" class="form-control" placeholder="Designation" id="des_add_val">
               <span class="input-group-btn">
                    <button class="btn btn-success" type="button" id="des_sub_btn">Add</button>
               </span>
               <span class="input-group-btn">
                    <button class="btn" type="button" id="des_close_btn">X</button>
               </span>
            </div>
        </div>
    <?php
    }
    ?>
    
<script type="text/javascript">
$(document).ready(function(){	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
});
</script>