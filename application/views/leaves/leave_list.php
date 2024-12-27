<?php
/* Awards view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Leave
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("leaves/add_leave") ?>" method="post" name="add_award" id="xin-form">
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="employee">Name of leave</label>
                   <input class="form-control" type="text" id="lname" name="lname">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="award_type">Leave Type</label>
                        
                         <select name="leavetype" id="leavetype" class="form-control" data-placeholder="Choose Leave Type...">
                              <option value="paid">Paid</option>
                              <option value="unpaid">unpaid</option>
                        </select>
                          
                       
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="gender">Gender</label>
                         <select name="gendertype" id="gendertype" class="form-control" data-placeholder="Choose Leave Type...">
                              <option value="male">male</option>
                              <option value="female">female</option>
                              <option value="both">both</option>
                        </select>
                      </div>
                    </div>
                  </div>
                   <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="days">Number of days</label>
                        <input class="form-control" placeholder="days" name="days" id="days" type="number">
                      </div>
                    </div>
                   
                   
                  </div>
                  
                </div>
                
                <div class="col-md-6">
                 
                </div>
              </div>
            
              <div class="form-group">
                <label for="award_information">leave Information</label>
                <textarea class="form-control" placeholder="leave Information" name="leave_information" cols="30" rows="3" id="leave_information"></textarea>
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
  <h2><strong>List All</strong> Leaves
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th> ID</th>
          <th>Leave Name</th>
          <th>Type of Leave</th>
          <th>No of days</th>
          <th>Gender</th>
          <th>Description</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<style type="text/css">
.hide-calendar .ui-datepicker-calendar { display:none !important; }
.hide-calendar .ui-priority-secondary { display:none !important; }
</style>
