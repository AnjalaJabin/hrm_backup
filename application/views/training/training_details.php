<?php
/*
* Training Detail view
*/
$session = $this->session->userdata('username');
?>

<div class="row m-b-1">
<div class="col-md-4">
  <div class="box box-block bg-white">
    <h2><strong>Training Detail</strong></h2>
    <table class="table table-striped m-md-b-0">
      <tbody>
        <tr>
          <th scope="row">Training Type</th>
          <td class="text-right"><?php echo $type;?></td>
        </tr>
        <?php $user = $this->Xin_model->read_user_info($session['user_id']);
				  	if($user[0]->user_role_id==1){?>
        <tr>
          <th scope="row">Trainer</th>
          <td class="text-right"><?php echo $trainer_first_name.' '.$trainer_last_name;?></td>
        </tr>
        <?php } ?>
        <tr>
          <th scope="row">Training Cost</th>
          <td class="text-right"><?php echo $this->Xin_model->currency_sign($training_cost);?></td>
        </tr>
        <tr>
          <th scope="row">Start Date</th>
          <td class="text-right"><?php echo $this->Xin_model->set_date_format($start_date);?></td>
        </tr>
        <tr>
          <th scope="row">End Date</th>
          <td class="text-right"><?php echo $this->Xin_model->set_date_format($finish_date);?></td>
        </tr>
        <tr>
          <th scope="row">Date</th>
          <td class="text-right"><?php echo $this->Xin_model->set_date_format($created_at);?></td>
        </tr>
      </tbody>
    </table>
    <?php if($description!='' && $description!='<p><br></p>'):?>
    <div class="the-notes info">
      <p><?php echo $description;?><br>
      </p>
    </div>
    <?php endif;?>
  </div>
</div>
<div class="col-md-8">
<div class="box box-block bg-white">
<div class="wizard" role="tabpanel">
<h2><strong>Details</strong></h2>
<div class="tab-content m-b-1">
<div role="tabpanel" class="tab-pane active fade in" id="detail" style="overflow:auto;">
<div class="col-md-6">
  <h2><strong>Training Employee(s)</strong></h2>
  <div class="items-list" id="all_employees_list">
    <?php if($employee_id!='') {?>
    <?php $employee_ids = explode(',',$employee_id); foreach($employee_ids as $assign_id) {?>
    <?php $e_name = $this->Xin_model->read_user_info($assign_id);?>
    <?php $_designation = $this->Designation_model->read_designation_information($e_name[0]->designation_id);?>
    <?php
		if($e_name[0]->profile_picture!='' && $e_name[0]->profile_picture!='no file') {
			$u_file = base_url().'uploads/profile/'.$e_name[0]->profile_picture;
		} else {
			if($e_name[0]->gender=='Male') { 
				$u_file = base_url().'uploads/profile/default_male.jpg';
			} else {
				$u_file = base_url().'uploads/profile/default_female.jpg';
			}
		} ?>
    <div class="il-item"> <?php if($user[0]->user_role_id==1):?><a class="text-black" href="<?php echo site_url()?>employees/detail/<?php echo $e_name[0]->user_id;?>/"> <?php endif;?>
      <div class="media">
        <div class="media-left">
          <div class="avatar box-48"> <img class="b-a-radius-circle" src="<?php echo $u_file;?>" alt=""> <i class="status bg-secondary bottom right"></i> </div>
        </div>
        <div class="media-body">
          <h6 class="media-heading"><?php echo $e_name[0]->first_name.' '.$e_name[0]->last_name;?></h6>
          <span class="text-muted"><?php echo $_designation[0]->designation_name;?></span> </div>
      </div>
      <div class="il-icon"><i class="fa fa-angle-right"></i></div>
      <?php if($user[0]->user_role_id==1):?></a><?php endif;?> </div>
    <?php } ?>
    <?php } else { ?>
    <span>&nbsp;</span>
    <?php } ?>
  </div>
</div>
<?php if($user[0]->user_role_id==1){?>
<div class="col-md-6">
  <form action="<?php echo site_url("training/update_status") ?>" method="post" name="update_status" id="update_status">
    <input type="hidden" name="token_status" id="token_status" value="<?php echo $training_id;?>">
    <h2><strong>Update Status</strong></h2>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="status">Performance</label>
          <select class="form-control" name="performance" data-plugin="select_hrm" data-placeholder="Performance">
            <option value="0" <?php if($performance=='0'):?> selected <?php endif;?>>Not Concluded</option>
            <option value="1" <?php if($performance=='1'):?> selected <?php endif;?>>Satisfactory</option>
            <option value="2" <?php if($performance=='2'):?> selected <?php endif;?>>Average</option>
            <option value="3" <?php if($performance=='3'):?> selected <?php endif;?>>Poor</option>
            <option value="4" <?php if($performance=='4'):?> selected <?php endif;?>>Excellent</option>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="status">Status</label>
          <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
            <option value="0" <?php if($training_status=='0'):?> selected <?php endif;?>>Pending</option>
            <option value="1" <?php if($training_status=='1'):?> selected <?php endif;?>>Started</option>
            <option value="2" <?php if($training_status=='2'):?> selected <?php endif;?>>Completed</option>
            <option value="3" <?php if($training_status=='3'):?> selected <?php endif;?>>Terminated</option>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="status">Remarks</label>
          <textarea class="form-control" name="remarks" rows="4" cols="15" placeholder="Admin Remarks"><?php echo $remarks;?></textarea>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary save">Save</button>
  </form>
</div>
<?php } else {?>
<div class="col-md-6">
<form action="module/training/hrm_json_training.php" method="post" name="update_status" id="update_status">
<input type="hidden" name="_token_status" value="<?php echo $training_id;?>">
<h2><strong>Trainer</strong></h2>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="status"><?php echo $trainer_first_name.' '.$trainer_last_name;?></label>
    </div>
  </div>
</div>
</div>
<?php } ?>
<div>&nbsp;</div>
</div>
<!-- tab -->
</div>
</div>
</div>
</div>
</div>
</div>
