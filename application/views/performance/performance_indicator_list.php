<?php
/* Performance Indicator view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong>Set New</strong> Indicator
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("performance_indicator/add_indicator") ?>" method="post" name="add_performance_indicator" id="xin-form" class="form-hrm">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-3 control-label">
                  <div class="form-group">
                    <label for="designation">Designation</label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <select class="select2" data-plugin="select_hrm" data-placeholder="Select Designation..." name="designation_id">
                      <option value=""></option>
                      <?php foreach($all_designations as $designation) {?>
                      <option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <h2><span> <strong>Technical Competencies</strong> </span></h2>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Customer Experience</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="customer_experience" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Marketing </label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="marketing" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Management</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="management" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Administration</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="administration" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Presentation Skill</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="presentation_skill" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Quality Of Work</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="quality_of_work" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Efficiency</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="efficiency" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                        <option value="4"> Expert / Leader</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <h2><span> <strong>Technical Competencies</strong> </span></h2>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Integrity</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="integrity" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Professionalism</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="professionalism" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Team Work</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="team_work" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Critical Thinking</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="critical_thinking" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Conflict Management</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="conflict_management" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Attendance</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="attendance" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group">
                      <label>Ability To Meet Deadline</label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <select name="ability_to_meet_deadline" class="form-control">
                        <option value="">None</option>
                        <option value="1"> Beginner</option>
                        <option value="2"> Intermediate</option>
                        <option value="3"> Advanced</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 control-label">
                    <div class="form-group"> &nbsp; </div>
                  </div>
                  <div class="col-md-5 control-label">
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary save">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white">
  <h2><strong>List All</strong> Performance Indicators
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Designation</th>
          <th>Department</th>
          <th>Added By</th>
          <th>Created At</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
