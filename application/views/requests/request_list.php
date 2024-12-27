<?php
/*
* Tickets view
*/
$session = $this->session->userdata('username');
?>

<div class="add-form" style="display:none;">
    <div class="box box-block bg-white">
        <h2>Create New Request
            <div class="add-record-btn">
                <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
            </div>
        </h2>
        <div class="row m-b-1">
            <div class="col-md-12">
                <form action="<?php echo site_url("requests/add_request") ?>" method="post" name="add_request" id="xin-form">
                    <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
                    <div class="bg-white">
                        <div class="box-block">
                            <?php if(!($my_request)){?>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="employees">Request By Employee</label>
                                    <select class="form-control"  name="employee_id" data-plugin="select_hrm" data-placeholder="Employee" >
                                        <option value=""></option>
                                        <?php foreach($all_employees as $employee) {
                                                ?>
                                                <option value="<?php echo $employee->user_id?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                            <?php } ?>
                                    </select>
                                </div>

                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
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
    <h2><strong>List All</strong> Employee Requests
        <div class="add-record-btn">
            <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> Add New</button>
        </div>
    </h2>
    <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table">
            <thead>
            <tr>
                <th>Action</th>
                <th> ID</th>
                <th>Employee Id</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created Date</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script>
    var my_request ='<?php echo $my_request;?>';
</script>