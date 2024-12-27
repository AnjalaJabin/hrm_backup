<?php
/*
* Tickets view
*/
$session = $this->session->userdata('username');
?>

<div class="add-form" style="display:none;">
    <div class="box box-block bg-white">
        <h2>Create New Asset
            <div class="add-record-btn">
                <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> Hide</button>
            </div>
        </h2>
        <div class="row m-b-1">
            <div class="col-md-12">
                <form action="<?php echo site_url("assets/add_asset") ?>" method="post" name="add_asset" id="xin-form">
                    <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
                    <div class="bg-white">
                        <div class="box-block">
                            <div class="row">
                                <div class="col-md-6">
                                        <label for="employees">Asset for Employee</label>
                                        <select class="form-control"  name="employee_id" data-plugin="select_hrm" data-placeholder="Employee" >
                                            <option value=""></option>
                                            <?php foreach($all_employees as $employee) {
                                                if($employee->ticket_eligibilty	){?>
                                                    ?>
                                                    <option value="<?php echo $employee->user_id?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
                                                <?php }} ?>
                                        </select>
                                    </div>
                                <div class="col-md-3">

                                        <label for="ticket_no" class="control-label">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="1">In-Use</option>
                                            <option value="2">Available</option>
                                            <option value="3">Not Available</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="purchase_date">Date</label>
                                            <input class="form-control date" placeholder="Date given" readonly name="date" type="text" value="">
                                        </div>

                                </div>
                            </div>
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
    <h2><strong>List All</strong> Assets
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
                <th>Date </th>
                <th>Assets</th>
                <th>Status</th>
                <th>Created Date</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
