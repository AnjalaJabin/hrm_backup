<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['performance_appraisal_id']) && $_GET['data']=='appraisal'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">Edit Performance Indicator</h4>
</div>
<form class="m-b-1" action="<?php echo site_url("performance_appraisal/update").'/'.$performance_appraisal_id; ?>" method="post" name="edit_appraisal" id="edit_appraisal">
  <input type="hidden" name="_method" value="EDIT">
  <input type="hidden" name="_token" value="<?php echo $performance_appraisal_id;?>">
  <div class="modal-body">
    <div class="row m-b-1">
      <div class="col-md-12">
        <div class="box box-block bg-white">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-3 control-label">
                  <div class="form-group">
                    <label for="employee">Employee</label>
                    <input type="hidden" name="emp_id" value="<?php echo $employee_id;?>">
                    <input type="hidden" name="cur_date" value="<?php echo $appraisal_year_month;?>">
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <select class="select2" data-plugin="select_hrm" data-placeholder="Select an Employee..." name="employee_id" id="employee_id">
                      <option value=""></option>
                      <?php foreach($all_employees as $employee) {?>
                      <option value="<?php echo $employee->user_id;?>" <?php if($employee_id==$employee->user_id):?> selected="selected" <?php endif;?>><?php echo $employee->first_name.' '.$employee->last_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 control-label">
                  <div class="form-group">
                    <label for="month_year">Select Month</label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <input class="form-control e_month_year" placeholder="Select Month" readonly id="month_year" name="month_year" type="text" value="<?php echo $appraisal_year_month;?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row m-b-1">
          <div class="col-md-6">
            <div class="box bg-white">
              <table class="table table-grey-head m-md-b-0">
                <thead>
                  <tr>
                    <th colspan="5">Technical Competencies</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th colspan="2">Indicator</th>
                    <th colspan="2">Expected Value</th>
                    <th>Set Value</th>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Customer Experience </td>
                    <td colspan="2">Intermediate</td>
                    <td><select name="customer_experience" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($customer_experience=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($customer_experience=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($customer_experience=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                        <option value="4" <?php if($customer_experience=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Marketing </td>
                    <td colspan="2">Advanced</td>
                    <td><select name="marketing" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($marketing=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($marketing=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($marketing=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                        <option value="4" <?php if($marketing=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Management </td>
                    <td colspan="2">Advanced</td>
                    <td><select name="management" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($management=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($management=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($management=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                        <option value="4" <?php if($management=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Administration </td>
                    <td colspan="2">Advanced</td>
                    <td><select name="administration" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($administration=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($administration=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($administration=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                        <option value="4" <?php if($administration=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Presentation Skill </td>
                    <td colspan="2">Expert / Leader</td>
                    <td><select name="presentation_skill" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($presentation_skill=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($presentation_skill=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($presentation_skill=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                        <option value="4" <?php if($presentation_skill=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Quality Of Work </td>
                    <td colspan="2">Expert / Leader</td>
                    <td><select name="quality_of_work" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($quality_of_work=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($quality_of_work=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($quality_of_work=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                        <option value="4" <?php if($quality_of_work=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Efficiency </td>
                    <td colspan="2">Expert / Leader</td>
                    <td><select name="efficiency" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($efficiency=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($efficiency=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($efficiency=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                        <option value="4" <?php if($efficiency=='4'):?> selected="selected" <?php endif;?>> Expert / Leader</option>
                      </select></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-6">
            <div class="box bg-white">
              <table class="table table-grey-head m-md-b-0">
                <thead>
                  <tr>
                    <th colspan="5">Behavioural / Organizational Competencies</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th colspan="2">Indicator</th>
                    <th colspan="2">Expected Value</th>
                    <th>Set Value</th>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Integrity</td>
                    <td colspan="2">Beginner</td>
                    <td><select name="integrity" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($integrity=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($integrity=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($integrity=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Professionalism</td>
                    <td colspan="2">Beginner</td>
                    <td><select name="professionalism" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($professionalism=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($professionalism=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($professionalism=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Team Work</td>
                    <td colspan="2">Intermediate</td>
                    <td><select name="team_work" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($team_work=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($team_work=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($team_work=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Critical Thinking</td>
                    <td colspan="2">Advanced</td>
                    <td><select name="critical_thinking" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($critical_thinking=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($critical_thinking=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($critical_thinking=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Conflict Management</td>
                    <td colspan="2">Intermediate</td>
                    <td><select name="conflict_management" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($conflict_management=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($conflict_management=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($conflict_management=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Attendance</td>
                    <td colspan="2">Intermediate</td>
                    <td><select name="attendance" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($attendance=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($attendance=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($attendance=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td scope="row" colspan="2">Ability To Meet Deadline</td>
                    <td colspan="2">Advanced</td>
                    <td><select name="ability_to_meet_deadline" class="form-control">
                        <option value="">None</option>
                        <option value="1" <?php if($ability_to_meet_deadline=='1'):?> selected="selected" <?php endif;?>> Beginner</option>
                        <option value="2" <?php if($ability_to_meet_deadline=='2'):?> selected="selected" <?php endif;?>> Intermediate</option>
                        <option value="3" <?php if($ability_to_meet_deadline=='3'):?> selected="selected" <?php endif;?>> Advanced</option>
                      </select></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="m-b-1">
        <div class="col-md-6">
          <div class="box box-block bg-white">
            <div class="form-group">
              <label for="remarks">Remarks</label>
              <textarea class="form-control textarea" placeholder="Remarks" name="remarks" cols="30" rows="15" id="remarks2"><?php echo $remarks;?></textarea>
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
            url : "<?php echo site_url("performance_appraisal/appraisal_list") ?>",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    	});
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	
		$('#remarks2').summernote({
		  height: 135,
		  minHeight: null,
		  maxHeight: null,
		  focus: false
		}); 
		
		/* Edit data */
		$("#edit_appraisal").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=appraisal&form="+action,
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
<?php } else if(isset($_GET['jd']) && isset($_GET['performance_appraisal_id']) && $_GET['data']=='view_appraisal' && $_GET['type']=='view_appraisal'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data">View Performance Appraisal</h4>
</div>
<div class="modal-body">
  <div class="row m-b-1">
    <div class="col-md-12">
      <div class="box box-block bg-white">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-3 control-label">
                <div class="form-group">
                  <label for="employee">Employee: </label>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <?php foreach($all_employees as $employee) {?>
                  <?php if($employee_id==$employee->user_id):?>
                  <?php echo $employee->first_name.' '.$employee->last_name;?>
                  <?php endif; } ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 control-label">
                <div class="form-group">
                  <label for="month_year">Appraisal Date: </label>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group"> <?php echo date("F, Y", strtotime($appraisal_year_month));?> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row m-b-1">
        <div class="col-md-6">
          <div class="box bg-white">
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-grey-head m-md-b-0">
              <thead>
                <tr>
                  <th colspan="5">Technical Competencies</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th colspan="2">Indicator</th>
                  <th colspan="2">Expected Value</th>
                  <th>Set Value</th>
                </tr>
                <tr>
                  <td scope="row" colspan="2">Customer Experience </td>
                  <td colspan="2">Intermediate</td>
                  <td><?php if($customer_experience=='1'):?>
                    Beginner
                    <?php elseif($customer_experience=='2'):?>
                    Intermediate
                    <?php elseif($customer_experience=='3'):?>
                    Advanced
                    <?php elseif($customer_experience=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;"> Not Set </span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2"> Marketing </td>
                  <td colspan="2"> Advanced </td>
                  <td><?php if($marketing=='1'):?>
                    Beginner
                    <?php elseif($marketing=='2'):?>
                    Intermediate
                    <?php elseif($marketing=='3'):?>
                    Advanced
                    <?php elseif($marketing=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;"> Not Set </span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2"> Management </td>
                  <td colspan="2"> Advanced </td>
                  <td><?php if($management=='1'):?>
                    Beginner
                    <?php elseif($management=='2'):?>
                    Intermediate
                    <?php elseif($management=='3'):?>
                    Advanced
                    <?php elseif($management=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;"> Not Set </span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2"> Administration </td>
                  <td colspan="2"> Advanced </td>
                  <td><?php if($administration=='1'):?>
                    Beginner
                    <?php elseif($administration=='2'):?>
                    Intermediate
                    <?php elseif($administration=='3'):?>
                    Advanced
                    <?php elseif($administration=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;"> Not Set </span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2"> Presentation Skill </td>
                  <td colspan="2"> Expert / Leader </td>
                  <td><?php if($presentation_skill=='1'):?>
                    Beginner
                    <?php elseif($presentation_skill=='2'):?>
                    Intermediate
                    <?php elseif($presentation_skill=='3'):?>
                    Advanced
                    <?php elseif($presentation_skill=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;"> Not Set </span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2"> Quality Of Work </td>
                  <td colspan="2"> Expert / Leader </td>
                  <td><?php if($quality_of_work=='1'):?>
                    Beginner
                    <?php elseif($quality_of_work=='2'):?>
                    Intermediate
                    <?php elseif($quality_of_work=='3'):?>
                    Advanced
                    <?php elseif($quality_of_work=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;"> Not Set </span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2"> Efficiency </td>
                  <td colspan="2"> Expert / Leader </td>
                  <td><?php if($efficiency=='1'):?>
                    Beginner
                    <?php elseif($efficiency=='2'):?>
                    Intermediate
                    <?php elseif($efficiency=='3'):?>
                    Advanced
                    <?php elseif($efficiency=='4'):?>
                    Expert / Leader
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;"> Not Set </span>
                    <?php endif;?></td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box bg-white">
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-grey-head m-md-b-0">
              <thead>
                <tr>
                  <th colspan="5">Behavioural / Organizational Competencies</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th colspan="2">Indicator</th>
                  <th colspan="2">Expected Value</th>
                  <th>Set Value</th>
                </tr>
                <tr>
                  <td scope="row" colspan="2">Integrity</td>
                  <td colspan="2">Beginner</td>
                  <td><?php if($integrity=='1'):?>
                    Beginner
                    <?php elseif($integrity=='2'):?>
                    Intermediate
                    <?php elseif($integrity=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2">Professionalism</td>
                  <td colspan="2">Beginner</td>
                  <td><?php if($professionalism=='1'):?>
                    Beginner
                    <?php elseif($professionalism=='2'):?>
                    Intermediate
                    <?php elseif($professionalism=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2">Team Work</td>
                  <td colspan="2">Intermediate</td>
                  <td><?php if($team_work=='1'):?>
                    Beginner
                    <?php elseif($team_work=='2'):?>
                    Intermediate
                    <?php elseif($team_work=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2">Critical Thinking</td>
                  <td colspan="2">Advanced</td>
                  <td><?php if($critical_thinking=='1'):?>
                    Beginner
                    <?php elseif($critical_thinking=='2'):?>
                    Intermediate
                    <?php elseif($critical_thinking=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2">Conflict Management</td>
                  <td colspan="2">Intermediate</td>
                  <td><?php if($conflict_management=='1'):?>
                    Beginner
                    <?php elseif($conflict_management=='2'):?>
                    Intermediate
                    <?php elseif($conflict_management=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2">Attendance</td>
                  <td colspan="2">Intermediate</td>
                  <td><?php if($attendance=='1'):?>
                    Beginner
                    <?php elseif($attendance=='2'):?>
                    Intermediate
                    <?php elseif($attendance=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?></td>
                </tr>
                <tr>
                  <td scope="row" colspan="2">Ability To Meet Deadline</td>
                  <td colspan="2">Advanced</td>
                  <td><?php if($ability_to_meet_deadline=='1'):?>
                    Beginner
                    <?php elseif($ability_to_meet_deadline=='2'):?>
                    Intermediate
                    <?php elseif($ability_to_meet_deadline=='3'):?>
                    Advanced
                    <?php else:?>
                    <span style="color:red;font - style: italic;line - height:2.4;">Not Set</span>
                    <?php endif;?></td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="m-b-1">
      <div class="col-md-12">
        <div class="box box-block bg-white">
          <div class="form-group">
            <label for="remarks"><strong>Remarks</strong></label>
            <?php echo htmlspecialchars_decode($remarks);?> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<?php }
?>
