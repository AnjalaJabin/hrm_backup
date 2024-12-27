<?php
/* Departments view
*/

$list_count = $this->Xin_model->get_all_departments();
if($list_count==0)
{
    $style='';
}
else
{
    $style='style="display:none;"';
}
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1 animated fadeInRight">
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <h2><strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_department');?></h2>
      <form class="m-b-1 add" method="post" action="<?php echo site_url("department/add_department") ?>" name="add_department" id="xin-form">
        <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_name');?></label>
          <input type="text" class="form-control" name="department_name" placeholder="<?php echo $this->lang->line('xin_name');?>">
        </div>
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_location');?></label>
          <select name="location_id" id="select2-demo-6" class="form-control"  data-placeholder="<?php echo $this->lang->line('xin_location');?>">
            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
            <?php foreach($all_locations as $location) {
            $company_data = $this->Xin_model->get_company_by_location($location->location_id);
            $company_name = $company_data[0]->name;
            ?>
            <option value="<?php echo $location->location_id;?>"> <?php echo $location->location_name.' - '.$company_name;?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="name"><?php echo $this->lang->line('xin_department_head');?></label>
          <select name="employee_id" id="select2-demo-6" data-plugin="select_hrm" class="form-control"  data-placeholder="<?php echo $this->lang->line('xin_department_head');?>">
            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
            <?php foreach($all_employees as $employee) {?>
            <?php
                /* get user_role */
				$user_role = $this->Xin_model->read_user_role_info($employee->user_role_id);
				?>
            <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?> (<?php echo $user_role[0]->role_name;?>)</option>
            <?php } ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?></button>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-block bg-white">
      <h2><strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_departments');?></h2>
      <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table">
          <thead>
            <tr>
              <th><?php echo $this->lang->line('xin_action');?></th>
              <th><?php echo $this->lang->line('xin_department_name');?></th>
              <th><?php echo $this->lang->line('xin_department_head');?></th>
              <th><?php echo $this->lang->line('xin_location');?></th>
              <th><?php echo $this->lang->line('xin_added_by');?></th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
  </div>
</div>
