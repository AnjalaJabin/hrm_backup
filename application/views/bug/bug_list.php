<?php
/* Company view
*/

$list_count = $this->Xin_model->get_all_companies();
if($list_count==0)
{
    $style='';
}
else
{
    $style='style="display:none;"';
}
?>
<?php $session = $this->session->userdata('username');?>

<div class="add-form" <?php echo $style; ?>>
  <div class="box box-block bg-white">
    <h2><strong>Report New</strong> Bug
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> <?php echo $this->lang->line('xin_hide');?></button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form method="post" name="add_bug" id="xin-form" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
        <div class="bg-white">
          <div class="box-block">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="company_name">Report</label>
                  <textarea class="form-control" rows="10" name="report" placeholder="Type Report Here..."></textarea>
                </div>
                
                
                <div class="form-group">
                    <h6>Screen Shot</h6>
                    <input type="file" name="image" id="image">
                    <br>
                    <small><?php echo $this->lang->line('xin_company_file_type');?></small> 
                </div>
                
                <div class="text-right">
                  <button type="submit" class="btn btn-primary save"> Submit Report <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
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
  <h2><strong><?php echo $this->lang->line('xin_list_all');?></strong> Reported Bugs
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> <?php echo $this->lang->line('xin_add_new');?></button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th><?php echo $this->lang->line('xin_action');?></th>
          <th>Report</th>
          <th>OS</th>
          <th>Browser</th>
          <th>Browser Version</th>
          <th>Date & Time</th>
          <th>Status</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
