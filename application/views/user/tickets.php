<?php
/* Ticket view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2>Create New Ticket
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form action="<?php echo site_url("tickets/add_ticket") ?>" method="post" name="add_ticket" id="xin-form">
          <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
          <input type="hidden" name="employee_id" value="<?php echo $session['user_id'];?>">
          <div class="bg-white">
            <div class="box-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="task_name">Subject</label>
                    <input class="form-control" placeholder="Subject" name="subject" type="text" value="">
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="ticket_priority" class="control-label">Priority</label>
                        <select name="ticket_priority" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Select Priority">
                          <option value=""></option>
                          <option value="1">Low</option>
                          <option value="2">Medium</option>
                          <option value="3">High</option>
                          <option value="4">Critical</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="description">Ticket Description</label>
                    <textarea class="form-control textarea" placeholder="Description" name="description" cols="30" rows="5" id="description"></textarea>
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
  <h2><strong>List All</strong> Tickets
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Ticket Code</th>
          <th>Subject</th>
          <th>Employee</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
