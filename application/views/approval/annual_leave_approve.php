<?php
/* Leave Detail view
*/
?>
<?php $session = $this->session->userdata('username');
$role_resources_ids = $this->Xin_model->user_role_resource();
?>
<?php $user = $this->Xin_model->read_user_info($session['user_id']);?>

<div class="row m-b-1">
    <div class="col-md-6">
        <div class="box box-block bg-white">
            <h2><strong>Leave Detail</strong></h2>
            <table class="table table-striped m-md-b-0">
                <tbody>
                <tr>
                    <th scope="row">Employee</th>
                    <td class="text-right"><?php echo $first_name.' '.$last_name;?></td>
                </tr>
                <tr>
                    <th scope="row">Applied On</th>
                    <td class="text-right"><?php echo $this->Xin_model->set_date_format($applied_on);?></td>
                </tr>
                <tr>
                    <th scope="row">From Date</th>
                    <td class="text-right"><?php echo $this->Xin_model->set_date_format($from_date);?></td>
                </tr>
                <tr>
                    <th scope="row">To Date</th>
                    <td class="text-right"><?php echo $this->Xin_model->set_date_format($end_date);?></td>
                </tr>
                </tbody>
            </table>
            <br>
        </div>
        <div class="box box-block bg-white">
            <h2><strong>Leave Statistics </strong> of <?php echo $first_name.' '.$last_name;?> (<?php echo date('Y'); ?>)</h2>
            <?php
            $count_leaves = $this->Timesheet_model->count_total_annual_leaves();
            $user_leaves =$this->Timesheet_model->count_total_annual_leaves_user($employee_id,date('Y'));

            $count_l=$count_leaves?$count_leaves[0]->annual_leave_count:30;
            $monthly_leave =$count_l/12;
            $last_leave = $this->Timesheet_model->getLatestAnnualLeaveForEmployee($employee_id);
            $currentDate = date('Y-m-d');

            // Get the start of the year
            //            $startOfYear = date('Y-01-01');
            if($last_leave)
                $last_doj = $last_leave[0]->end_date;
            else
                $last_doj = $user[0]->date_of_joining;

            // Calculate the difference in days between the current date and the start of the year
            $daysSinceStartOfYear = intval((strtotime($currentDate) - strtotime($last_doj)) / (60 * 60 * 24));
            $months=$daysSinceStartOfYear/30;



            ?>
            <?php
            if(($monthly_leave * $months))
                $count_data = ($user_leaves / ($monthly_leave * $months))*100;
            else
                $count_data=0;

            $bal_percentage =$months*$monthly_leave/$count_l*100;
            // progress
            if($count_data <= 20) {
                $progress_class = 'progress-success';
            } else if($count_data > 20 && $count_data <= 50){
                $progress_class = 'progress-info';
            } else if($count_data > 50 && $count_data <= 75){
                $progress_class = 'progress-warning';
            } else {
                $progress_class = 'progress-danger';
            }
            ?>
            <div id="last doj">
                <p><strong>End Date of Last Vacation</strong></p>
                <p><?php echo date('jS M Y', strtotime($last_doj));?></p>
            </div>
            <div id="leave-balance">
                <p><strong>Leave Balance (<?php echo $months*$monthly_leave?>/<?php echo $count_l?>)</strong></p>
                <progress class="progress <?php echo $progress_class;?>" value="<?php echo $bal_percentage;?>" max="100"></progress>
            </div>
            <div id="leave-statistics">
                <p><strong>Annual Leave in Current Year(<?php echo $user_leaves;?>)</strong></p>
                <progress class="progress <?php echo $progress_class;?>" value="<?php echo $count_data;?>" max="100"></progress>
            </div>
        </div>
    </div>
        <div class="col-md-6">
            <div class="box box-block bg-white">
                <h2><strong>Update Status</strong></h2>
                <form action="<?php echo site_url("approvals/update_annual_leave_status").'/'.$leave_id;?>/" method="post" name="update_status" id="update_status">
                    <input type="hidden" name="_token_status" value="<?php echo $leave_id;?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                                    <option value="1" <?php if($approval=='1'):?> selected <?php endif; ?>>Pending</option>
                                    <option value="2" <?php if($approval=='2'):?> selected <?php endif; ?>>Approved</option>
                                    <option value="3" <?php if($approval=='3'):?> selected <?php endif; ?>>Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control textarea" placeholder="Remarks" name="remarks" id="remarks" cols="30" rows="3"><?php echo $remarks;?></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary save">Save</button>
                </form>
            </div>
        </div>

</div>
