<?php
/* Holidays view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1">
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <h2><strong>Add New</strong> Holiday</h2>
      <form class="m-b-1" action="<?php echo site_url("timesheet/add_holiday") ?>" method="post" name="add_holiday" id="xin-form">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name">Event Name</label>
              <input type="text" class="form-control" name="event_name" placeholder="Event Name">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date">Start Date</label>
              <input class="form-control date" placeholder="Start Date" readonly name="start_date" type="text">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date">End Date</label>
              <input class="form-control date" placeholder="End Date" readonly name="end_date" type="text">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="15" id="description"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="is_publish">Status</label>
              <select name="is_publish" class="select2" data-plugin="select_hrm" data-placeholder="Choose Status...">
                <option value="1">Published</option>
                <option value="0">Un Published</option>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary save">Save</button>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-block bg-white">
      <h2><strong>List All</strong> Holidays</h2>
      <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
          <thead>
            <tr>
              <th style="width:150px;">Action</th>
              <th>Event Name</th>
              <th>Status</th>
              <th>Start Date</th>
              <th>Start End</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
  </div>
</div>
