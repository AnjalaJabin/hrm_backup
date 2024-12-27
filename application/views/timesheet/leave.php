<?php
/* Leave Application view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add Leave</strong>
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("timesheet/add_leave") ?>" method="post" name="add_leave" id="xin-form">
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="leave_type" class="control-label">Leave Type</label>
                    <select class="form-control" name="leave_type" data-plugin="select_hrm" data-placeholder="Leave Type">
                      <option value=""></option>
                      <?php foreach($all_leave_types as $type) {?>
                      <option value="<?php echo $type->leave_type_id;?>"> <?php echo $type->type_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input class="form-control date" placeholder="Start Date" readonly name="start_date" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input class="form-control date" placeholder="End Date" readonly name="end_date" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employees" class="control-label">Leave for Employee</label>
                        <select class="form-control" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee">
                          <option value=""></option>
                          <?php foreach($all_employees as $employee) {?>
                          <option value="<?php echo $employee->user_id?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Remarks</label>
                    <textarea class="form-control textarea" placeholder="Remarks" name="remarks" cols="30" rows="15" id="remarks"></textarea>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="summary">Leave Reason</label>
                <textarea class="form-control" placeholder="Leave Reason" name="reason" cols="30" rows="3" id="reason"></textarea>
              </div>
              <button type="submit" class="btn btn-primary save">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white">
  <h2><strong>List All</strong> Leave
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add Leave</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Leave Type</th>
          <th>Request Duration</th>
          <th>Applied On</th>
          <th>Reason</th>
          <th>Approval Status</th>
          <th>Status</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
