<?php
/* Employees view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong><?php echo $this->lang->line('xin_add_new');?></strong> <?php echo $this->lang->line('xin_employee');?>
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form "><i class="fa fa-minus icon"></i> <?php echo $this->lang->line('xin_hide');?></button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("employees/add_employee") ?>" method="post" name="add_employee" id="xin-form">
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="first_name"><?php echo $this->lang->line('xin_employee_first_name');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name');?>" name="first_name" type="text" tabindex="1">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="last_name" class="control-label"><?php echo $this->lang->line('xin_employee_last_name');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_last_name');?>" name="last_name" type="text" tabindex="2">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="department"><?php echo $this->lang->line('xin_employee_department');?></label>
                        <select class="form-control" name="department_id" id="aj_department" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>" tabindex="5">
                          <option value="">Select One</option>
                          <?php foreach($all_departments as $department) {
                            $company_data = $this->Xin_model->get_company_by_department($department->department_id);
                            $company_name = $company_data[0]->name;
                          ?>
                          <option value="<?php echo $department->department_id?>"><?php echo $department->department_name.' - '.$company_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" id="designation_ajax">
                        <label for="designation"><?php echo $this->lang->line('xin_designation');?></label>
                        <select class="form-control" name="designation_id" data-placeholder="<?php echo $this->lang->line('xin_designation');?>" tabindex="6">
                          <option value="">Select One</option>
                        </select>
                      </div>
                    </div>
                      <div class="col-md-6">
                          <div class="form-group" id="reporting_ajax">
                              <label for="reporting">Reporting To</label>
                              <select id="reporting_to" class="form-control" name="reporting_to" data-placeholder="Reporting To" tabindex="6">
                                  <option value="">Select One</option>
                              </select>
                          </div>
                      </div> <div class="col-md-6">
                          <div class="form-group" >
                              <label for="reporting">Nationality</label>
                              <input class="form-control" placeholder="Nationality" name="nationality" type="text" tabindex="10">

                          </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email');?>" name="email" type="text" tabindex="10">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="contact_no" class="control-label"><?php echo $this->lang->line('xin_contact_number');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_contact_number');?>" name="contact_no" type="text" tabindex="13">
                      </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="employee_id"><?php echo $this->lang->line('dashboard_employee_id');?></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_id');?>" name="employee_id" type="text" tabindex="3">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="date_of_joining" class="control-label"><?php echo $this->lang->line('xin_employee_doj');?></label>
                        <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('xin_employee_doj');?>" name="date_of_joining" type="text" tabindex="4">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="role">Permission</label>
                        <select class="form-control" name="role" data-placeholder="Permission" tabindex="7">
                          <option value="">Select One</option>
                          <?php foreach($all_user_roles as $role) {?>
                          <option value="<?php echo $role->role_id?>"><?php echo $role->role_name?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="gender" class="control-label"><?php echo $this->lang->line('xin_employee_gender');?></label>
                        <select class="form-control" name="gender" data-placeholder="<?php echo $this->lang->line('xin_employee_gender');?>" tabindex="8">
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="date_of_birth"><?php echo $this->lang->line('xin_employee_dob');?></label>
                        <input class="form-control date_of_birth" readonly placeholder="<?php echo $this->lang->line('xin_employee_dob');?>" name="date_of_birth" type="text" tabindex="12">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="office_shift_id" class="control-label"><?php echo $this->lang->line('xin_employee_office_shift');?></label>
                        <select class="form-control" name="office_shift_id" data-placeholder="<?php echo $this->lang->line('xin_employee_office_shift');?>" tabindex="11">
                          <?php foreach($all_office_shifts as $shift) {?>
                          <option value="<?php echo $shift->office_shift_id?>"><?php echo $shift->shift_name?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
                 <div class="form-group">
                <label for="address"><?php echo $this->lang->line('xin_employee_address');?></label>
                <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_address');?>" name="address" cols="30" rows="3" id="address" tabindex="16"></textarea>
              </div>
              <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white  g-bg-white">
  <div class="card-header"><h2><strong><?php echo $this->lang->line('xin_list_all');?></strong> <?php echo $this->lang->line('xin_employees');?>
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form g-add-new-btn"><i class="fa fa-plus icon"></i> <?php echo $this->lang->line('xin_add_new');?></button>
    </div></div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th><?php echo $this->lang->line('xin_action');?></th>
          <th><?php echo $this->lang->line('xin_employees_id');?></th>
          <th><?php echo $this->lang->line('xin_employees_full_name');?></th>
<!--          <th>--><?php //echo $this->lang->line('module_company_title');?><!--</th>-->
          <th><?php echo $this->lang->line('dashboard_email');?></th>
          <th>Permission</th>
          <th><?php echo $this->lang->line('xin_designation');?></th>
          <th><?php echo $this->lang->line('dashboard_xin_status');?></th>
        </tr>
      </thead>
    </table>
  </div>
</div>