<?php
/* Hourly Wage/Rate view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1">
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <h2><strong>Add New</strong> Hourly Wage</h2>
      <form class="m-b-1 add" method="post" action="<?php echo site_url("payroll/add_hourly_rate") ?>" name="add_hourly_rate" id="xin-form">
        <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="hourly_grade">Title</label>
              <input class="form-control" placeholder="HourlyWages Title" name="hourly_grade" type="text">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="hourly_rate">Hourly Rate</label>
              <input class="form-control" placeholder="Hourly Rate" name="hourly_rate" type="text">
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary save">Save</button>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-block bg-white">
      <h2><strong>List All</strong> Hourly Wages</h2>
      <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table">
          <thead>
            <tr>
              <th>Action</th>
              <th>HourlyWage Title</th>
              <th>Hourly Rate</th>
              <th>Created By</th>
              <th>Created Date</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
  </div>
</div>
