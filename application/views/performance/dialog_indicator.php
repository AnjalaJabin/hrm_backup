<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['performance_indicator_id']) && $_GET['data']=='indicator'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Performance Indicator</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("performance_indicator/update").'/'.$performance_indicator_id; ?>" method="post" name="edit_indicator" id="edit_indicator">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $performance_indicator_id;?>">
  <input type="hidden" name="ext_name" value="<?php echo $designation_id;?>">
  <div class="modal-body">
    <div class="row m-b-1">
      <div class="col-md-12">
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
                    <option value="<?php echo $designation->designation_id?>" <?php if($designation->designation_id==$designation_id):?> selected="selected" <?php endif;?>><?php echo $designation->designation_name?></option>
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
                      <option value="1" <?php if($customer_experience=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($customer_experience=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($customer_experience=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      <option value="4" <?php if($customer_experience=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
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
                      <option value="1" <?php if($marketing=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($marketing=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($marketing=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      <option value="4" <?php if($marketing=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
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
                      <option value="1" <?php if($management=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($management=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($management=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      <option value="4" <?php if($management=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
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
                      <option value="1" <?php if($administration=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($administration=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($administration=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      <option value="4" <?php if($administration=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
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
                      <option value="1" <?php if($presentation_skill=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($presentation_skill=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($presentation_skill=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      <option value="4" <?php if($presentation_skill=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
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
                      <option value="1" <?php if($quality_of_work=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($quality_of_work=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($quality_of_work=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      <option value="4" <?php if($quality_of_work=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
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
                      <option value="1" <?php if($efficiency=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($efficiency=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($efficiency=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      <option value="4" <?php if($efficiency=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
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
                      <option value="1" <?php if($integrity=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($integrity=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($integrity=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
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
                      <option value="1" <?php if($professionalism=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($professionalism=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($professionalism=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
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
                      <option value="1" <?php if($team_work=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($team_work=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($team_work=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
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
                      <option value="1" <?php if($critical_thinking=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($critical_thinking=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($critical_thinking=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
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
                      <option value="1" <?php if($conflict_management=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($conflict_management=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($conflict_management=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
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
                      <option value="1" <?php if($attendance=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($attendance=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($attendance=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
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
                      <option value="1" <?php if($ability_to_meet_deadline=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                      <option value="2" <?php if($ability_to_meet_deadline=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                      <option value="3" <?php if($ability_to_meet_deadline=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Update</button>
  </div>
</form>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script> 
<script type="text/javascript">
 $(document).ready(function(){
					
		// On page load: datatable
		var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
		"ajax": {
            url : "<?php echo site_url("performance_indicator/performance_indicator_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		
		/* Edit data */
		$("#edit_indicator").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=indicator&form="+action,
				cache: false,
				success: function (JSON) {
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
				}
			});
		});
	});	
  </script> 
<?php } else if(isset($_GET['jd']) && isset($_GET['performance_indicator_id']) && $_GET['data']=='view_indicator' && $_GET['type']=='view_indicator'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Performance Indicator</h4>
</div>
<form method="post" name="view_performance_indicator" id="view_performance_indicator" class="form-hrm">
  <div class="modal-body">
    <div class="row m-b-1">
      <div class="col-md-12">
        <div class="bg-white">
          <div class="box-block">
            <div class="row">
              <div class="col-md-3 control-label">
                <div class="form-group">
                  <label for="designation">Designation: </label>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <?php foreach($all_designations as $designation) {?>
                  <?php if($designation->designation_id==$designation_id):?>
                  <?php echo $designation->designation_name?>
                  <?php endif;?>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <h2><span> <strong>Technical Competencies</strong> </span></h2>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Customer Experience: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($customer_experience=='1'):?>
                    Beginner
                    <?php elseif($customer_experience=='2'):?>
                    Intermediate
                    <?php elseif($customer_experience=='3'):?>
                    Advanced
                    <?php elseif($customer_experience=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Marketing: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($marketing=='1'):?>
                    Beginner
                    <?php elseif($marketing=='2'):?>
                    Intermediate
                    <?php elseif($marketing=='3'):?>
                    Advanced
                    <?php elseif($marketing=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Management: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($management=='1'):?>
                    Beginner
                    <?php elseif($management=='2'):?>
                    Intermediate
                    <?php elseif($management=='3'):?>
                    Advanced
                    <?php elseif($management=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Administration: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($administration=='1'):?>
                    Beginner
                    <?php elseif($administration=='2'):?>
                    Intermediate
                    <?php elseif($administration=='3'):?>
                    Advanced
                    <?php elseif($administration=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Presentation Skill: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($presentation_skill=='1'):?>
                    Beginner
                    <?php elseif($presentation_skill=='2'):?>
                    Intermediate
                    <?php elseif($presentation_skill=='3'):?>
                    Advanced
                    <?php elseif($presentation_skill=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Quality Of Work: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($quality_of_work=='1'):?>
                    Beginner
                    <?php elseif($quality_of_work=='2'):?>
                    Intermediate
                    <?php elseif($quality_of_work=='3'):?>
                    Advanced
                    <?php elseif($quality_of_work=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Efficiency: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($efficiency=='1'):?>
                    Beginner
                    <?php elseif($efficiency=='2'):?>
                    Intermediate
                    <?php elseif($efficiency=='3'):?>
                    Advanced
                    <?php elseif($efficiency=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <h2><span> <strong>Technical Competencies</strong> </span></h2>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Integrity: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($integrity=='1'):?>
                    Beginner
                    <?php elseif($integrity=='2'):?>
                    Intermediate
                    <?php elseif($integrity=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Professionalism: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($professionalism=='1'):?>
                    Beginner
                    <?php elseif($professionalism=='2'):?>
                    Intermediate
                    <?php elseif($professionalism=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Team Work: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($team_work=='1'):?>
                    Beginner
                    <?php elseif($team_work=='2'):?>
                    Intermediate
                    <?php elseif($team_work=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Critical Thinking: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($critical_thinking=='1'):?>
                    Beginner
                    <?php elseif($critical_thinking=='2'):?>
                    Intermediate
                    <?php elseif($critical_thinking=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Conflict Management: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($conflict_management=='1'):?>
                    Beginner
                    <?php elseif($conflict_management=='2'):?>
                    Intermediate
                    <?php elseif($conflict_management=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Attendance: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($attendance=='1'):?>
                    Beginner
                    <?php elseif($attendance=='2'):?>
                    Intermediate
                    <?php elseif($attendance=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 control-label">
                  <div class="form-group">
                    <label>Ability To Meet Deadline: </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <?php if($ability_to_meet_deadline=='1'):?>
                    Beginner
                    <?php elseif($ability_to_meet_deadline=='2'):?>
                    Intermediate
                    <?php elseif($ability_to_meet_deadline=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>
<?php }
?>
