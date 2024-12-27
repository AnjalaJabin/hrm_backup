<?php
/* Job List/Post view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Add New</strong> Job
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("job_post/add_job") ?>" method="post" name="add_job" id="xin-form">
          <input type="hidden" name="_user" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="title">Job Title</label>
                        <input class="form-control" placeholder="Job Title" name="job_title" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="job_type">Job Type</label>
                        <select class="form-control" name="job_type" data-plugin="select_hrm" data-placeholder="Job Type">
                          <option value=""></option>
                          <?php foreach($all_job_types as $job_type) {?>
                          <option value="<?php echo $job_type->job_type_id?>"><?php echo $job_type->type?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                          <option value="1">Published</option>
                          <option value="2">Un Published</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="vacancy">Number of Positions</label>
                        <input class="form-control" placeholder="Number of Positions" name="vacancy" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="date_of_closing" class="control-label">Date of Closing</label>
                        <input class="form-control date" placeholder="Date of Closing" readonly name="date_of_closing" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" name="gender" data-plugin="select_hrm" data-placeholder="Gender">
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="No Preference">No Preference</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="experience" class="control-label">Minimum Experience</label>
                        <select class="form-control" name="experience" data-plugin="select_hrm" data-placeholder="Minimum Experience">
                          <option value="Fresh">Fresh</option>
                          <option value="1 Year">1 Year</option>
                          <option value="2 Years">2 Years</option>
                          <option value="3 Years">3 Years</option>
                          <option value="4 Years">4 Years</option>
                          <option value="5 Years">5 Years</option>
                          <option value="6 Years">6 Years</option>
                          <option value="7 Years">7 Years</option>
                          <option value="8 Years">8 Years</option>
                          <option value="9 Years">9 Years</option>
                          <option value="10 Years">10 Years</option>
                          <option value="10+ Years">10+ Years</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="long_description">Long Description</label>
                    <textarea class="form-control textarea" placeholder="Long Description" name="long_description" cols="30" rows="15" id="long_description"></textarea>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="short_description">Short Description</label>
                <textarea class="form-control" placeholder="Short Description" name="short_description" cols="30" rows="3"></textarea>
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
  <h2><strong>List All</strong> Jobs
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Title</th>
          <th>Designation</th>
          <th>Job Type</th>
          <th>No. of Positions</th>
          <th>Closing Date</th>
          <th>Status</th>
          <th>Added Date</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
