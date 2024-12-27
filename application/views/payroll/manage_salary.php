<?php
/* Manage Salary view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1">
  <div class="col-md-6">
    <div class="box box-block bg-white">
      <h2><strong>Manage Salary</strong></h2>
      <div class="row">
        <div class="col-md-12">
          <form class="m-b-1 add form-hrm" method="post" name="set_salary_details" id="set_salary_details">
            <div class="row">
              <div class="col-md-3 control-label">
                <div class="form-group">
                  <label for="department">Employee</label>
                </div>
              </div>
              <div class="col-md-9">
                <div class="form-group">
                  <select id="employee_id" name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                  <option value="0">All Employees</option>
                  <?php foreach($all_employees as $employee) {?>
                  <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?> (<?php echo $employee->username;?>)</option>
                  <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 control-label"> </div>
              <div class="col-md-5">
                <div class="form-group">
                  <button type="submit" class="btn btn-primary save">Search</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white">
      <h2><strong>List All</strong> Employee Salaries</h2>
      <div class="table-responsive" data-pattern="priority-columns">
        <form method="post" name="user_salary_template" id="user_salary_template" action="<?php echo site_url("payroll/user_salary_template") ?>">
          <table class="table table-striped table-bordered dataTable" id="xin_table">
            <thead>
              <tr>
                <th>View</th>
                <th>Employee Name</th>
                <th>Username</th>
                <th>Designation</th>
                <th>Hourly</th>
                <th>Monthly</th>
              </tr>
            </thead>
          </table>
          <div id="update_data">
            <input type="submit" name="update" value="Update" class="btn btn-primary save primary-btn-block col-right">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
