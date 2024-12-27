<?php
/* Transfer view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Transfer
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("transfers/add_transfer") ?>" method="post" name="add_transfer" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee">Employee to Transfer</label>
                    <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose an Employee...">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="transfer_date">Transfer Date</label>
                        <input class="form-control date" placeholder="Transfer Date" readonly name="transfer_date" type="text">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="transfer_location">Transfer To (Company)</label>
                        <select class="form-control" id="company_id" data-placeholder="Select Company..." name="transfer_company">
                          <option value="">Select One</option>
                          <?php foreach($all_companies as $company) {?>
                          <option value="<?php echo $company->company_id?>"><?php echo $company->name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" id="location_ajax">
                        <label for="transfer_location">Transfer To (Location)</label>
                        <select class="form-control" id="location_id" data-placeholder="Select Location..." name="transfer_location">
                          <option value="">Select One</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group" id="department_ajax">
                        <label for="transfer_department">Transfer To (Department)</label>
                        <select class="form-control" id="department_id" data-placeholder="Select Department..." name="transfer_department">
                          <option value="">Select One</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" id="designation_ajax">
                        <label for="transfer_department">Designation</label>
                        <select class="form-control" id="designation_id" data-placeholder="Select Designation..." name="transfer_designation">
                          <option value="">Select One</option>
                        </select>
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
  <h2><strong>List All</strong> Transfers
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee Name</th>
          <th>Transfer Date</th>
          <th>Transfer To (Department)</th>
          <th>Transfer To (Location)</th>
          <th>Status</th>
          <th>Added By</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
