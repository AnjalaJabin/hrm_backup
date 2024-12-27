<?php
/* Awards view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Award
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("awards/add_award") ?>" method="post" name="add_award" id="xin-form">
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee">Employee</label>
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
                        <label for="award_type">Award Type</label>
                        <select name="award_type_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Choose Award Type...">
                          <option value=""></option>
                          <?php foreach($all_award_types as $award_type) {?>
                          <option value="<?php echo $award_type->award_type_id;?>"><?php echo $award_type->award_type;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="award_date">Date</label>
                        <input class="form-control date" placeholder="Award Date" readonly name="award_date" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="month_year">Month & Year</label>
                        <input class="form-control d_month_year" placeholder="Month & Year of Award" readonly name="month_year" type="text">
                      </div>
                    </div>
                  </div>
                  
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="15" id="description"></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="gift">Gift</label>
                        <input class="form-control" placeholder="Gift" name="gift" type="text">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="cash">Cash</label>
                        <input class="form-control" placeholder="Cash" name="cash" type="text">
                      </div>
                    </div>
                    <div class="col-md-6">
                    <div class='form-group'>
                      <div><label for="photo">Award Photo</label></div>
                      	<span class="btn btn-primary btn-file">
                          Browse <input type="file" name="award_picture" id="award_picture">
                        </span>
                      <br>
                      <small>Upload files only: gif,png,jpg,jpeg</small> </div>
                  </div>
                  </div>
              <div class="form-group">
                <label for="award_information">Award Information</label>
                <textarea class="form-control" placeholder="Award Information" name="award_information" cols="30" rows="3" id="award_information"></textarea>
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
  <h2><strong>List All</strong> Awards
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Award Name</th>
          <th>Gift</th>
          <th>Cash Price</th>
          <th>Month & Year</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<style type="text/css">
.hide-calendar .ui-datepicker-calendar { display:none !important; }
.hide-calendar .ui-priority-secondary { display:none !important; }
</style>
