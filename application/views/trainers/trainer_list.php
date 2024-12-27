<?php
/* Trainers view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Trainer
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("trainers/add_trainer") ?>" method="post" name="add_trainer" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input class="form-control" placeholder="First Name" name="first_name" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="last_name" class="control-label">Last Name</label>
                        <input class="form-control" placeholder="Last Name" name="last_name" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input class="form-control" placeholder="Contact Number" name="contact_number" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input class="form-control" placeholder="Email" name="email" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="designation">Designation</label>
                        <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="Designation">
                          <option value=""></option>
                          <?php foreach($all_designations as $designation) {?>
                          <option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="expertise">Expertise</label>
                    <textarea class="form-control textarea" placeholder="Expertise" name="expertise" cols="30" rows="5" id="expertise"></textarea>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" placeholder="Address" name="address" cols="30" rows="3" id="address"></textarea>
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
  <h2><strong>List All</strong> Trainers
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Full Name</th>
          <th>Designation</th>
          <th>Contact Number</th>
          <th>Email</th>
          <th>Address</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
