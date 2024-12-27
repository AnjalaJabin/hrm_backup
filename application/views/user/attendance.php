<?php
/* Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1">
  <div class="col-md-8">
    <div class="box box-block bg-white">
      <div class="row">
        <div class="col-md-12">
          <h2><strong>Select Date</strong></h2>
          <div class="row">
            <div class="col-md-12">
            <form class="add form-hrm" method="post" name="attendance_datewise_report" id="attendance_datewise_report" action="ajax_table.php">
              <input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
              <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $session['user_id'];?>">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <input class="form-control attendance_date" placeholder="Select Date" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-d');?>">
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <input class="form-control attendance_date" placeholder="Select Date" readonly id="end_date" name="end_date" type="text" value="<?php echo date('Y-m-d');?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group"> &nbsp;
                    <button type="submit" class="btn btn-primary save">Get</button>
                  </div>
                </div>
              </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row m-b-1">
  <div class="col-md-12">
    <div class="box box-block bg-white">
      <h2><strong>Attendance</strong></h2>
      <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
          <thead>
            <tr>
              <th>Status</th>
              <th>Date</th>
              <th>Clock IN</th>
              <th>Clock OUT</th>
              <th>Late</th>
              <th>Early Leaving</th>
              <th>Overtime</th>
              <th>Total Work</th>
              <th>Total Rest</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
