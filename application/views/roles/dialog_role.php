<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['role_id']) && $_GET['data']=='role'){
    $role_resources_ids = explode(',',$role_resources);
    ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data">Edit User Role</h4>
    </div>
    <form class="m-b-1" action="<?php echo site_url("roles/update").'/'.$role_id; ?>" method="post" name="edit_role" id="edit_role">
        <input type="hidden" name="_method" value="EDIT">
        <input type="hidden" name="_token" value="<?php echo $role_id;?>">
        <input type="hidden" name="ext_name" value="<?php echo $role_name;?>">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role_name">Role Name</label>
                                <input class="form-control" placeholder="Role Name" name="role_name" type="text" value="<?php echo $role_name;?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <input type="checkbox" name="role_resources[]" value="0" checked style="display:none;"/>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role_access">Select Access</label>
                                <select class="form-control custom-select" id="role_access_modal" name="role_access" data-plugin="select_hrm" data-placeholder="Select Access">
                                    <option value="">&nbsp;</option>
                                    <option value="1" <?php if($role_access==1):?> selected="selected" <?php endif;?>>All Menu Access</option>
                                    <option value="2" <?php if($role_access==2):?> selected="selected" <?php endif;?>>Custom Menu Access</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="resources">Resources</label>
                                <div id="all_resources">
                                    <div class="demo-section k-content">
                                        <div>
                                            <div id="treeview_m1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div id="all_resources">
                                    <div class="demo-section k-content">
                                        <div>
                                            <div id="treeview_m2"></div>
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
                    url : "<?php echo site_url("roles/role_list") ?>",
                    type : 'GET'
                },
                "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });

            /* Edit data */
            $("#edit_role").submit(function(e){
                e.preventDefault();
                var obj = $(this), action = obj.attr('name');
                $('.save').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=1&edit_type=role&form="+action,
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
    <script>

        jQuery("#treeview_m1").kendoTreeView({
            checkboxes: {
                checkChildren: true,
                template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
            },
            check: onCheck,
            dataSource: [

                { id: "", class: "role-checkbox-modal custom-control-input", text: "Organization", check: "<?php if(isset($_GET['role_id'])) { if(in_array('1',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "1", items: [
// sub 1
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Company", check: "<?php if(isset($_GET['role_id'])) { if(in_array('3',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "3",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Location", check: "<?php if(isset($_GET['role_id'])) { if(in_array('4',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "4",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Department", check: "<?php if(isset($_GET['role_id'])) { if(in_array('5',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "5",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Designation", check: "<?php if(isset($_GET['role_id'])) { if(in_array('6',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "6",},

                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Announcements", check: "<?php if(isset($_GET['role_id'])) { if(in_array('8',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "8",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Policies", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('9',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "9",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Expenses", check: "<?php if(isset($_GET['role_id'])) { if(in_array('10',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "10",},
                    ]}, // sub 1 end
                { id: "", class: "role-checkbox-modal custom-control-input", text: "Employees",  add_info: "", value: "11", check: "<?php if(isset($_GET['role_id'])) {
                        if(in_array('11',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Employees List", check: "<?php if(isset($_GET['role_id'])) { if(in_array('13',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/View/Delete", value: "13",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Set Roles", check: "<?php if(isset($_GET['role_id'])) { if(in_array('14',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "14",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Awards", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('15',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "15",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Transfers", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('16',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "16",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Resignations", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('17',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "17",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Travels", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('18',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "18",},

                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Promotions", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('20',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "20",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Complaints", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('21',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "21",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Warnings", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('22',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "22",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Terminations", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('23',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "23",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Employees Last Login", check: "<?php if(isset($_GET['role_id'])) {
                                if(in_array('26',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "View", value: "26",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Employees Exit", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('27',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "27",}
                    ]},
                //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Performance", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('240',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "", value: "240",  items: [
                //        { id: "", class: "role-checkbox-modal custom-control-input", text: "Performance Indicator", check: "<?php //if(isset($_GET['role_id'])) {
//                                if(in_array('24',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/View/Delete", value: "24",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Give Performance Appraisal", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('25',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/View/Delete", value: "25",},
                    // ]},
                //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Timesheet",  add_info: "", value: "28", check: "<?php //if(isset($_GET['role_id'])) {
//                        if(in_array('28',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", items: [
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Attendance", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('29',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "View", value: "29"},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Date wise Attendance Report", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('30',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "View", value: "30",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Update Attendance", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('31',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/View/Delete", value: "31",},

                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Office Shifts", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('34',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "34",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Holidays", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('35',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "35",},
                    // ]},
                { id: "", class: "role-checkbox-modal custom-control-input", text: "Payroll",  add_info: "", value: "36", check: "<?php if(isset($_GET['role_id'])) {
                        if(in_array('36',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", items: [
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Payroll Templates", check: "<?php if(isset($_GET['role_id'])) { if(in_array('38',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Create/Edit/Delete", value: "38",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Hourly Wages", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('39',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "39",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Manage Salary", check: "<?php if(isset($_GET['role_id'])) { if(in_array('40',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Update/View", value: "40",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Leaves", check: "<?php if(isset($_GET['role_id'])) { if(in_array('32',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/View/Delete", value: "32",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Generate Payslip", check: "<?php if(isset($_GET['role_id'])) { if(in_array('41',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Generate/View", value: "41",},
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Payment History", check: "<?php if(isset($_GET['role_id'])) { if(in_array('42',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "View Payslip", value: "42",},
                    ]},
                //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Projects", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('7',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "7",},
                //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Worksheet (Tasks)", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('33',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/View/Delete", value: "33",},
                //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Tickets", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('19',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Create/Edit/View/Delete", value: "19",},

                //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Recruitment", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('43',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "", value: "43",  items: [
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Jobs Listing <small>frontend</small>", check: "<?php //if(isset($_GET['role_id'])) {
//                                if(in_array('44',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "View", value: "44"},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Job Posts", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('45',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "45",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Job Candidates", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('46',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Update Status/Delete", value: "46",},
                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Job Interviews", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('47',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Delete", value: "47",},
                    // ]},
            ]
        });

        jQuery("#treeview_m2").kendoTreeView({
            checkboxes: {
                checkChildren: true,
                template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
            },
            check: onCheck,
            dataSource: [
//
//                { id: "", class: "role-checkbox-modal custom-control-input", text: "Training",  add_info: "", value: "48", check: "<?php //if(isset($_GET['role_id'])) {
//                        if(in_array('48',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", items: [
//                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Training List", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('49',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/View/Delete", value: "49"},
//                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Training Type", check: "<?php ////if(isset($_GET['role_id'])) { if(in_array('50',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>////", add_info: "Add/Edit/Delete", value: "50",},
//                        //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Trainers List", check: "<?php ////if(isset($_GET['role_id'])) { if(in_array('51',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>////", add_info: "Add/Edit/Delete", value: "51",},
//                    ]},
//                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Employees Directory", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('52',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "52",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Settings", check: "<?php if(isset($_GET['role_id'])) { if(in_array('53',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "View/Update", value: "53",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Constants", check: "<?php if(isset($_GET['role_id'])) { if(in_array('54',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "54",},
                //{ id: "", class: "role-checkbox-modal custom-control-input", text: "Company Documents", check: "<?php //if(isset($_GET['role_id'])) { if(in_array('57',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>//", add_info: "Add/Edit/Delete", value: "57",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Bank",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('58',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "58",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Account & Payments",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('59',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "59",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Contacts",  check: "<?php if(isset($_GET['role_id'])) { if(in_array('60',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "60",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Approvals", check: "<?php if(isset($_GET['role_id'])) { if(in_array('100',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/Delete", value: "100",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Flight Tickets", check: "<?php if(isset($_GET['role_id'])) { if(in_array('101',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Add/Edit/View/Delete", value: "101",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Annual Leave", check: "<?php if(isset($_GET['role_id'])) { if(in_array('102',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "Create/Edit/View/Delete", value: "102",},
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Leave Salary", check: "<?php if(isset($_GET['role_id'])) { if(in_array('103',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "103",  },
                { id: "", class: "role-checkbox-modal custom-control-input",  text: "Liability Report", check: "<?php if(isset($_GET['role_id'])) { if(in_array('110',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "110",  },
                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Gratuity", check: "<?php if(isset($_GET['role_id'])) { if(in_array('104',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "104",  },
                                { id: "", class: "role-checkbox-modal custom-control-input", text: "Compensatory Leave", check: "<?php if(isset($_GET['role_id'])) { if(in_array('105',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "105",  },
                                        { id: "", class: "role-checkbox-modal custom-control-input", text: "Employee Assets", check: "<?php if(isset($_GET['role_id'])) { if(in_array('106',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "", value: "106",  },
                                                { id: "", class: "role-checkbox-modal custom-control-input", text: "Employee Requests", check: "<?php if(isset($_GET['role_id'])) {if(in_array('107',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "View", value: "107"},
                                                { id: "", class: "role-checkbox-modal custom-control-input", text: "End Of Service", check: "<?php if(isset($_GET['role_id'])) {if(in_array('108',$role_resources_ids)): echo 'checked'; else: echo ''; endif; }?>", add_info: "View", value: "108"},

                                            ]
                                        });

        // show checked node IDs on datasource change
        function onCheck() {
            var checkedNodes = [],
                treeView = jQuery("#treeview").data("kendoTreeView"),
                message;
//checkedNodeIds(treeView.dataSource.view(), checkedNodes);
            jQuery("#result").html(message);
        }
        $(document).ready(function(){
            $("#role_access_modal").change(function(){
                var sel_val = $(this).val();
                if(sel_val=='1') {
                    $('.role-checkbox-modal').prop('checked', true);
                } else {
                    $('.role-checkbox-modal').attr("checked", false);
                }
            });
        });
    </script>
<?php }
?>
