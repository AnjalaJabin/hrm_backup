<?php
if(isset($_REQUEST['month']) && !empty($_REQUEST['month']) && isset($_REQUEST['year']) && !empty($_REQUEST['year'])){
    $selected_date = $_REQUEST['month'].'-'.$_REQUEST['year'];
}else{
    $selected_date = date('m-Y');
}

$session = $this->session->userdata('username');

$date_split = explode('-',$selected_date);
$selected_month = $date_split[0];
$selected_year = $date_split[1];
$days_in_this_month = date("t",strtotime('01-'.$selected_date));
?>
<style>
.table thead th:nth-child(2){
  position: sticky;
  left: 0;
  z-index: 2;
  background-color:#fff;
}

.table tbody td:nth-child(2){
  position: sticky;
  left: 0;
  z-index: 2;
  background-color:#fff;
}
</style>
<div class="box box-block bg-white">
  <h2><strong>Attendance Export</strong>
    <div class="add-record-btn">
        <label class="m-0">
            <form action="<?php echo site_url('timesheet/attendance_export'); ?>" method="get">
                <table>
                    <tr>
                        <td>
                            <select name="month">
                            <?php
                            for($i=1;$i<=12; $i++){
                                echo '<option '.($i==$selected_month? "selected" : "").' value="'.$i.'">'.$i.'</option>';
                            }
                            ?>
                            </select>
                        </td>
                        <td>
                            <select name="year">
                            <?php
                            for($i=date('Y')-1;$i<=date('Y'); $i++){
                                echo '<option '.($i==$selected_year? "selected" : "").' value="'.$i.'">'.$i.'</option>';
                            }
                            ?>
                            </select>
                        </td>
                        <td>
                            <input type="submit" value="GO"/>
                        </td>
                    </tr>
                </table>
            </form>
        </label>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table_input">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Employee</th>
                <?php
                for($i=1; $i<=$days_in_this_month; $i++){
                    echo '<th>'.$i.'-'.$selected_date.'<br/>'.date('l',strtotime($i.'-'.$selected_date)).'</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            
            $projects = $this->Xin_model->all_active_employees();
            $slno = 1;
            foreach($projects as $r) {
        		echo '<tr>';
        		echo '<td>'.$slno.'</td>';
        		echo '<td style="min-width:250px">'.$r->first_name.' '.$r->last_name.'</td>';
                for($i=1; $i<=$days_in_this_month; $i++){
                    $time_value = $this->Xin_model->get_total_task_hours_by_employee($r->user_id,$i.'-'.$selected_date);
                    if($time_value=='0.0'){
                        $time_value = '';
                    }
                    echo '<td>'.$time_value.'</td>';
                }
        		echo '</tr>';
        		$slno++;
    	    }
    	    
            ?>
        </tbody>
    </table>
  </div>
</div>