<?php
/* Travel view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Travel
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("travel/add_travel") ?>" method="post" name="add_travel" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee_id">Employee</label>
                    <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input class="form-control date" placeholder="End Date" readonly name="start_date" type="text">
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
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="visit_purpose">Purpose of Visit</label>
                        <input class="form-control" placeholder="Purpose of Visit" name="visit_purpose" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="visit_place">Place of Visit</label>
                        <input class="form-control" placeholder="Place of Visit" name="visit_place" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="travel_mode">Travel Mode</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Travel Mode" name="travel_mode">
                          <option value="1">By Bus</option>
                          <option value="2">By Train</option>
                          <option value="3">By Plane</option>
                          <option value="4">By Taxi</option>
                          <option value="5">By Rental Car</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="arrangement_type">Arrangement Type</label>
                        <select class="select2" data-plugin="select_hrm" data-placeholder="Arrangement Type" name="arrangement_type">
                          <?php foreach($travel_arrangement_types as $travel_arr_type) {?>
                          <option value="<?php echo $travel_arr_type->arrangement_type_id;?>"> <?php echo $travel_arr_type->type;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="expected_budget">Expected Travel Budget</label>
                        <input class="form-control" placeholder="Expected Travel Budget" name="expected_budget" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="actual_budget">Actual Travel Budget</label>
                        <input class="form-control" placeholder="Actual Travel Budget" name="actual_budget" type="text">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="10" id="description"></textarea>
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
  <h2><strong>List All</strong> Travels
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Travel Purpose</th>
          <th>Visit Place</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Approval Status</th>
          <th>Added By</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
