<?php
/*
* Ticket Detail view
*/
$session = $this->session->userdata('username');
$user_info = $this->Xin_model->read_user_info($session['user_id']);
?>

<div class="row m-b-1">
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <h2><strong>Ticket</strong> Detail</h2>
      <?php
				if($ticket_priority==1): $priority = 'Low'; elseif($ticket_priority==2): $priority = 'Medium'; elseif($ticket_priority==3): $priority = 'High'; elseif($ticket_priority==4): $priority = 'Critical';  endif;
				?>
      <table class="table table-striped m-md-b-0">
        <tbody>
          <tr>
            <th scope="row">Subject</th>
            <td class="text-right"><?php echo $subject;?></td>
          </tr>
          <tr>
            <th scope="row">Employee</th>
            <td class="text-right"><?php echo $first_name.' '.$last_name;?></td>
          </tr>
          <tr>
            <th scope="row">Priority</th>
            <td class="text-right"><?php echo $priority;?></td>
          </tr>
          <tr>
            <th scope="row">Date</th>
            <td class="text-right"><?php
                    $created_at = date('h:i A', strtotime($created_at));
                    $_date = explode(' ',$created_at);
                    $edate = $this->Xin_model->set_date_format($_date[0]);
                    echo $_created_at = $edate. ' '. $created_at;?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php if($user_info[0]->user_role_id==1 || 1==1) {?>
    <!-- assigned to-->
    <div class="box box-block bg-white">
      <h2><strong>Assigned To</strong></h2>
      <?php $assigned_ids = explode(',',$assigned_to);?>
      <div class="row">
        <form action="<?php echo site_url("tickets/assign_ticket") ?>" method="post" name="assign_ticket" id="assign_ticket">
          <input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $ticket_id;?>">
          <div class="box-block">
            <div class="form-group">
              <label for="employees" class="control-label">Employee</label>
              <select multiple class="form-control" name="assigned_to[]" data-plugin="select_hrm" data-placeholder="Employee">
                <?php foreach($all_employees as $employee) {?>
                <option value="<?php echo $employee->user_id?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected <?php endif; ?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                <?php } ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary save">Save</button>
          </div>
        </form>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="col-md-8">
    <div class="box box-block bg-white">
      <div class="wizard" role="tabpanel">
        <ul class="nav nav-tabs m-b-1" role="tablist">
          <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#detail" role="tab"><i class="fa fa-home"></i> Detail</a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#comment" role="tab"><i class="fa fa-comment"></i> Comment</a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#notebook" role="tab"><i class="fa fa-pencil"></i> Note</a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#attachment" role="tab"><i class="fa fa-paperclip"></i> Ticket Files</a> </li>
        </ul>
        <div class="tab-content m-b-1">
          <div role="tabpanel" class="tab-pane active fade in" id="detail" style="overflow:auto;">
            <div class="info">
              <blockquote class="blockquote mb-1 mb-md-0"><?php echo html_entity_decode($description);?></blockquote>
            </div>
            <div class="col-md-6">
              <h2><strong>Assigned To</strong></h2>
              <div class="items-list" id="all_employees_list">
                <?php if($assigned_to!='') {?>
                <?php $employee_ids = explode(',',$assigned_to); foreach($employee_ids as $assign_id) {?>
                <?php $e_name = $this->Xin_model->read_user_info($assign_id);?>
                <?php $_designation = $this->Designation_model->read_designation_information($e_name[0]->designation_id);?>
                <?php
						if($e_name[0]->profile_picture!='' && $e_name[0]->profile_picture!='no file') {
							$u_file = base_url().'uploads/profile/'.$e_name[0]->profile_picture;
						} else {
							if($e_name[0]->gender=='Male') { 
								$u_file = base_url().'uploads/profile/default_male.jpg';
							} else {
								$u_file = base_url().'uploads/profile/default_female.jpg';
							}
						} ?>
                <div class="il-item"> <?php if($user_info[0]->user_role_id==1):?><a class="text-black" href="<?php echo site_url()?>employees/detail/<?php echo $e_name[0]->user_id;?>"><?php endif;?>
                  <div class="media">
                    <div class="media-left">
                      <div class="avatar box-48"> <img class="b-a-radius-circle" src="<?php echo $u_file;?>" alt=""> <i class="status bg-secondary bottom right"></i> </div>
                    </div>
                    <div class="media-body">
                      <h6 class="media-heading"><?php echo $e_name[0]->first_name.' '.$e_name[0]->last_name;?></h6>
                      <span class="text-muted"><?php echo $_designation[0]->designation_name;?></span> </div>
                  </div>
                  <div class="il-icon"><i class="fa fa-angle-right"></i></div>
                  <?php if($user_info[0]->user_role_id==1):?></a><?php endif;?> </div>
                <?php } ?>
                <?php } else { ?>
                <span>&nbsp;</span>
                <?php } ?>
              </div>
            </div>
            <div class="col-md-6">
              <form action="<?php echo site_url("tickets/update_status") ?>" method="post" name="update_status" id="update_status">
                <input type="hidden" name="status_ticket_id" id="status_ticket_id" value="<?php echo $ticket_id;?>">
                <h2><strong>Update Status</strong></h2>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                        <option value="1" <?php if($ticket_status=='1'):?> selected <?php endif; ?>>Open</option>
                        <option value="2" <?php if($ticket_status=='2'):?> selected <?php endif; ?>>Closed</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="status">Remarks</label>
                      <textarea class="form-control" name="remarks" rows="4" cols="15" placeholder="Admin Remarks"><?php echo $ticket_remarks;?></textarea>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary save">Save</button>
              </form>
            </div>
            <div>&nbsp;</div>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="comment">
            <form action="<?php echo site_url("tickets/set_comment") ?>" method="post" name="set_comment" id="set_comment">
              <input type="hidden" id="comment_ticket_id" name="comment_ticket_id" value="<?php echo $ticket_id;?>">
              <input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
              <div class="box-block">
                <div class="form-group">
                  <textarea name="xin_comment" id="xin_comment" class="form-control" rows="4" placeholder="Comment"></textarea>
                </div>
                <button type="submit" class="btn btn-primary save">Save</button>
              </div>
            </form>
            <div class="clear"></div>
            <div class="table-responsive">
              <table class="table table-hover mb-md-0" id="xin_comment_table" style="width:100%;">
                <thead>
                  <tr>
                    <th>All Comments</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane fade" id="attachment">
            <form action="<?php echo site_url("tickets/add_attachment") ?>" enctype="multipart/form-data" method="post" name="add_attachment" id="add_attachment">
              <input type="hidden" name="user_file_id" id="user_file_id" value="<?php echo $session['user_id'];?>">
              <input type="hidden" name="_token_file" id="_token_file" value="<?php echo $ticket_id;?>">
              <input type="hidden" name="c_ticket_id" id="c_ticket_id" value="<?php echo $ticket_id;?>">
              <div class="bg-white">
                <div class="box-block">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="task_name">Title</label>
                        <input class="form-control" placeholder="Title" name="file_name" type="text" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class='form-group'>
                        <h6>Attachment File</h6>
                        <span class="btn btn-primary btn-file">
                        	Browse <input type="file" name="attachment_file" id="attachment_file">
                        </span>
                        <br>
                        <small>Upload files only: gif,png,jpg,jpeg,txt,doc,docx,xls,xlsx</small> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" placeholder="Description" name="file_description" rows="4" id="file_description"></textarea>
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary save">Save</button>
                </div>
              </div>
            </form>
            <div class="clear"></div>
            <h2><strong>Attachment List</strong></h2>
            <div class="table-responsive">
              <table class="table table-hover table-striped table-bordered table-ajax-load" id="xin_attachment_table" style="width:100%;">
                <thead>
                  <tr>
                    <th>Option</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date and Time</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane animated fadeInRight fade" id="notebook">
            <form action="<?php echo site_url("tickets/add_note") ?>" method="post" name="add_note" id="add_note">
              <input type="hidden" name="token_note_id" id="token_note_id" value="<?php echo $ticket_id;?>">
              <input type="hidden" name="_uid" value="<?php echo $session['user_id'];?>">
              <div class="box-block">
                <div class="form-group">
                  <textarea name="ticket_note" id="ticket_note" class="form-control" rows="7" placeholder="Ticket Note..."><?php echo $ticket_note;?></textarea>
                </div>
                <button type="submit" class="btn btn-primary save">Save</button>
              </div>
            </form>
          </div>
          <!-- tab --> 
        </div>
      </div>
    </div>
  </div>
</div>
