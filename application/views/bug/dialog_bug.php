<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['bug_id']) && $_GET['data']=='bug'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="icon-pencil7"></i> Edit Bug Report </h4>
</div>
<form class="m-b-1" action="<?php echo site_url("bug/update").'/'.$id; ?>" enctype="multipart/form-data" method="post" name="edit_bug" id="edit_bug">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $_GET['bug_id'];?>">
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="report">Report</label>
          <textarea class="form-control" rows="10" name="report" placeholder="Type Report Here..."><?php echo $report;?></textarea>
        </div>
        
        <div class="form-group">
          <h6>Screen Shot</h6>
         	 <input type="file" name="image" id="image">
          <?php if($image!=''){?>
          <br/><br>
          <small><?php echo $this->lang->line('xin_company_file_type');?></small> </div>
          <div> <a href="<?php echo site_url();?>uploads/bugs/<?php echo $image;?>" target="blank"><img src="<?php echo site_url();?>uploads/bugs/<?php echo $image;?>" alt="" style="max-width:150px; max-height:150px;"></a> </div>
          <?php } ?>
          
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_update');?></button>
  </div>
</form>
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : "<?php echo site_url("bug/bug_list") ?>",
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 

		/* Edit data */
		$("#edit_bug").submit(function(e){
			var fd = new FormData(this);
			var obj = $(this), action = obj.attr('name');
			fd.append("is_ajax", 2);
			fd.append("edit_type", 'bug');
			fd.append("form", action);
			e.preventDefault();
			$('.save').prop('disabled', true);
			$.ajax({
				url: e.target.action,
				type: "POST",
				data:  fd,
				contentType: false,
				cache: false,
				processData:false,
				success: function(JSON)
				{
					if (JSON.error != '') {
						toastr.error(JSON.error);
						$('.save').prop('disabled', false);
					} else {
						xin_table.api().ajax.reload(function(){ 
							toastr.success(JSON.result);
						}, true);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				},
				error: function() 
				{
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
				} 	        
		   });
		});
	});	
  </script>
<?php } else if(isset($_GET['jd']) && $_GET['data']=='view_bug' && isset($_GET['bug_id']) ){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="icon-eye4"></i> View Bug Details </h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
  <table class="footable-details table table-striped table-hover toggle-circle">
    <tbody>
      <tr>
        <th>Report</th>
        <td style="display: table-cell;"><?php echo $report;?></td>
      </tr>
      <tr>
        <th>Browser</th>
        <td style="display: table-cell;"><?php echo $browser;?></td>
      </tr>
      <tr>
        <th>Browser Version</th>
        <td style="display: table-cell;"><?php echo $browserver;?></td>
      </tr>
      <tr>
        <th>OS</th>
        <td style="display: table-cell;"><?php echo $os;?></td>
      </tr>
      <tr>
        <th>Screen Shot</th>
        <td style="display: table-cell;"><?php if($image!=''){?>
          <div> <a href="<?php echo site_url();?>uploads/bugs/<?php echo $image;?>" target="blank"><img src="<?php echo site_url();?>uploads/bugs/<?php echo $image;?>" alt="" style="max-width:300px; max-height:300px;"></a> </div>
          <?php } ?></td>
      </tr>
    </tbody>
  </table>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>
</form>
<?php }
?>