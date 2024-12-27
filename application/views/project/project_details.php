<?php
/* Task Details view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $u_created = $this->Xin_model->read_user_info($session['user_id']);?>
<?php $assigned_ids = explode(',',$assigned_to);?>
<?php
//status
if($status == 0) {
	$nstatus = 'Not Started';
} else if($status ==1){
	$nstatus = 'In Progress';
} else if($status ==2){
	$nstatus = 'Completed';
} else {
	$nstatus = 'Deferred';
}
//priority
if($priority == 1) {
	$epriority = '<span class="label label-danger">Highest</span>';
} else if($priority ==2){
	$epriority = '<span class="label label-info">High</span>';
} else if($priority ==3){
	$epriority = '<span class="label label-primary">Normal</span>';
} else {
	$epriority = '<span class="label label-success">Low</span>';
}
?>
<div class="row m-b-1">
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <h5><strong><?php echo $this->lang->line('xin_project');?>: </strong> <?php echo $title;?></h5>
      <table class="table table-striped m-md-b-0">
        <tbody>
          <tr>
            <th scope="row"><?php echo $this->lang->line('dashboard_xin_status');?></th>
            <td class="text-right"><?php echo $nstatus;?></td>
          </tr>
          <tr>
            <th scope="row"><?php echo $this->lang->line('xin_start_date');?></th>
            <td class="text-right"><?php echo $this->Xin_model->set_date_format($start_date);?></td>
          </tr>
          <tr>
            <th scope="row"><?php echo $this->lang->line('xin_end_date');?></th>
            <td class="text-right"><?php echo $this->Xin_model->set_date_format($end_date);?></td>
          </tr>
          <tr>
            <th scope="row"><?php echo $this->lang->line('xin_createdp');?></th>
            <td class="text-right"><?php echo $this->Xin_model->set_date_format($created_at);?></td>
          </tr>
          <tr>
            <th scope="row">Priority</th>
            <td class="text-right"><?php echo $epriority;?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php if($u_created[0]->user_role_id==1){?>
    <!-- assigned to-->
    <div class="box box-block bg-white">
      <h5><?php echo $this->lang->line('xin_project_users');?></h5>
      <div class="row">
        <form action="<?php echo site_url("project/assign_project") ?>" method="post" name="assign_project" id="assign_project">
          <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id;?>">
          <div class="box-block">
            <div class="form-group">
              <label for="employees" class="control-label"><?php echo $this->lang->line('xin_employee');?></label>
              <select multiple class="form-control" name="assigned_to[]" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee');?>">
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected <?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                <?php } ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary save">Save</button>
          </div>
        </form>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="col-md-8">
    <div class="box box-block bg-white">
      <div class="wizard" role="tabpanel">
        <ul class="nav nav-tabs m-b-1" role="tablist">
          <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#detail" role="tab"><i class="fa fa-home"></i> Detail</a> </li>
        </ul>
        <div class="tab-content m-b-1">
          <div role="tabpanel" class="tab-pane animated fadeInRight active fade in" id="detail" style="overflow:auto;">
            <div class="info">
              <blockquote class="blockquote mb-1 mb-md-0"> <?php echo html_entity_decode($description);?> </blockquote> 
            </div>
            <div class="col-md-6">
              <h2><strong>Assigned To</strong></h2>
              <div class="items-list" id="all_employees_list">
                <?php if($assigned_to!='' && $assigned_to!='None') {?>
                <?php $employee_ids = explode(',',$assigned_to); foreach($employee_ids as $assign_id) {?>
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
                <div class="il-item"> <?php if($u_created[0]->user_role_id==1):?><a class="text-black" href="<?php echo site_url();?>employees/detail/<?php echo $e_name[0]->user_id;?>/"><?php endif; ?>
                  <div class="media">
                    <div class="media-left">
                      <div class="avatar box-48"> <img class="b-a-radius-circle" src="<?php echo $u_file;?>" alt=""> <i class="status bg-secondary bottom right"></i> </div>
                    </div>
                    <div class="media-body">
                      <h6 class="media-heading"><?php echo $e_name[0]->first_name.' '.$e_name[0]->last_name;?></h6>
                      <span class="text-muted"><?php echo $_designation[0]->designation_name;?></span> </div>
                  </div>
                  <div class="il-icon"><i class="fa fa-angle-right"></i></div>
                  <?php if($u_created[0]->user_role_id==1):?></a><?php endif; ?> </div>
                <?php } ?>
                <?php } else { ?>
                <span>&nbsp;</span>
                <?php } ?>
              </div>
            </div>
            <div class="col-md-6">
              <form action="<?php echo site_url("project/update_status") ?>" method="post" name="update_status" id="update_status">
                <input type="hidden" name="project_id" value="<?php echo $project_id;?>">
                <input type="hidden" id="progres_val" name="progres_val" value="<?php echo $progress;?>">
                <h2><strong>Update Status</strong></h2>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="progress">Progress</label>
                      <input type="text" id="range_grid">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                        <option value="0" <?php if($status=='0'):?> selected <?php endif; ?>>Not Started</option>
                        <option value="1" <?php if($status=='1'):?> selected <?php endif; ?>>In Progress</option>
                        <option value="2" <?php if($status=='2'):?> selected <?php endif; ?>>Completed</option>
                        <option value="3" <?php if($status=='3'):?> selected <?php endif; ?>>Deferred</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="status">Priority</label>
                      <select class="form-control" name="priority" data-plugin="select_hrm" data-placeholder="Priority">
                        <option value="1" <?php if($priority=='1'):?> selected <?php endif; ?>>Highest</option>
                        <option value="2" <?php if($priority=='2'):?> selected <?php endif; ?>>High</option>
                        <option value="3" <?php if($priority=='3'):?> selected <?php endif; ?>>Normal</option>
                        <option value="4" <?php if($priority=='4'):?> selected <?php endif; ?>>Low</option>
                      </select>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary save">Save</button>
              </form>
            </div>
            <div>&nbsp;</div>
          </div>
          <!-- tab --> 
        </div>
      </div>
    </div>
  </div>
</div>
