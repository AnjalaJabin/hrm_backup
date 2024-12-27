<?php $result = $this->Job_post_model->ajax_job_user_information($job_id);?>
<?php
?>
<div class="form-group">
    <label for="interviewees">Interviewees (Selected Candidates)</label>
    <select multiple class="form-control" name="interviewees[]" data-plugin="select_hrm" data-placeholder="Candidates">
    <option value=""></option>
    <?php foreach($result as $results) {?>
        <option value="<?php echo $results->application_id;?>"><?php echo $results->name;?></option>
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
});
</script>