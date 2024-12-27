<?php
/* Office Shift view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Office Shift
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("timesheet/add_office_shift") ?>" method="post" name="add_office_shift" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-10">
                  <div class="form-group">
                    <label for="name">Shift Name</label>
                    <input class="form-control" placeholder="Shift Name" name="shift_name" type="text" value="" id="name">
                  </div>
                  <div class="form-group row">
                    <label for="time" class="col-md-2">Monday</label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-1" placeholder="In Time" readonly name="monday_in_time" type="text" value="">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-1" placeholder="Out Time" readonly name="monday_out_time" type="text" value="">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary clear-time" data-clear-id="1">Clear</button>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="time" class="col-md-2">Tuesday</label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-2" placeholder="In Time" readonly name="tuesday_in_time" type="text" value="">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-2" placeholder="Out Time" readonly name="tuesday_out_time" type="text" value="">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary clear-time" data-clear-id="2">Clear</button>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="time" class="col-md-2">Wednesday</label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-3" placeholder="In Time" readonly name="wednesday_in_time" type="text" value="">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-3" placeholder="Out Time" readonly name="wednesday_out_time" type="text" value="">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary clear-time" data-clear-id="3">Clear</button>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="time" class="col-md-2">Thursday</label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-4" placeholder="In Time" readonly name="thursday_in_time" type="text" value="">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-4" placeholder="Out Time" readonly name="thursday_out_time" type="text" value="">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary clear-time" data-clear-id="4">Clear</button>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="time" class="col-md-2">Friday</label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-5" placeholder="In Time" readonly name="friday_in_time" type="text" value="">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-5" placeholder="Out Time" readonly name="friday_out_time" type="text" value="">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary clear-time" data-clear-id="5">Clear</button>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="time" class="col-md-2">Saturday</label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-6" placeholder="In Time" readonly name="saturday_in_time" type="text" value="">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-6" placeholder="Out Time" readonly name="saturday_out_time" type="text" value="">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary clear-time" data-clear-id="6">Clear</button>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="time" class="col-md-2">Sunday</label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-7" placeholder="In Time" readonly name="sunday_in_time" type="text" value="">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-7" placeholder="Out Time" readonly name="sunday_out_time" type="text" value="">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary clear-time" data-clear-id="7">Clear</button>
                    </div>
                  </div>
                </div>
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
  <h2><strong>List All</strong> Office Shift
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Option</th>
          <th>Day</th>
          <th>Monday</th>
          <th>Tuesday</th>
          <th>Wednesday</th>
          <th>Thursday</th>
          <th>Friday</th>
          <th>Saturday</th>
          <th>Sunday</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
