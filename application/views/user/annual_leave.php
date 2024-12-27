<?php
/* Leave view

*/
?>
<?php $session = $this->session->userdata('username');?>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>-->


<br>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#home">Employee List</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#menu1">Annual Leave Details</a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div id="home" class="tab-pane active"><br>
        <div class="box box-block bg-white">
            <h2><strong>List All</strong> Employee Leave Balance

            </h2>
            <div class="table-responsive" data-pattern="priority-columns">
                <table class="table table-striped table-bordered dataTable" id="xin_table_employee_list">
                    <thead>
                    <tr>
                        <!--                <th>Action</th>-->
                        <th> ID</th>

                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>End Date of Last Vacation</th>
                        <th>Months Completed</th>
                        <th>Current Leave Balance(Actual)</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
    <div id="menu1" class="tab-pane fade"><br>

<div class="add-form" style="display:none;">
    <div class="box box-block bg-white">
        <h2><strong>Add Leave</strong>
            <div class="add-record-btn">
                <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
            </div>
        </h2>
        <div class="row m-b-1">
            <div class="col-md-12">
                <form action="<?php echo site_url("employee/annual_leave/add_leave") ?>" method="post" name="add_leave" id="xin-form">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $session['user_id'];?>">
                    <div class="bg-white">
                        <div class="box-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employees">Annual Leave for Employee</label>
                                        <select class="form-control" id="employee-select" name="employee_id" data-plugin="select_hrm" data-placeholder="Employee" >
                                            <option value=""></option>
                                            <?php foreach($all_employees as $employee) {?>
                                                <option value="<?php echo $employee->user_id?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="balance" class="control-label">Leave Balance</label>
                                        <input id="leave_balance" class="form-control " placeholder="Leave Balance" readonly name="balance"  type="text" value="">

                                    </div>
                                </div>
                            </div>
                            <div class="row">

                            <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_date">Start Date</label>
                                                <input class="form-control date" placeholder="Start Date" readonly name="start_date" type="text" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date">End Date</label>
                                                <input class="form-control date" placeholder="End Date" readonly name="end_date" type="text" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Remarks</label>
                                        <textarea class="form-control textarea" placeholder="Remarks" name="remarks" cols="30" rows="15" id="remarks"></textarea>
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
    <h2><strong>List All</strong> Annual Leave
        <div class="add-record-btn">
            <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Apply Now </button>
        </div>
    </h2>
    <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
            <thead>
            <tr>
                <th>Action</th>
                <th>Employee ID</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Request Duration</th>
                <th>Applied On</th>
                <th>Approval Status</th>
                <th>Status</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
