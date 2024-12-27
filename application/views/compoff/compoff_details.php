<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    ?>

    <div class="box box-block bg-white">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
            <h5 class="modal-title" id="edit-modal-data"> <strong> All Compensatory Leaves </strong> For Employee <?php echo $compoff_data[0]->emp_id."-".$compoff_data[0]->first_name." ".$compoff_data[0]->last_name."(".$compoff_data[0]->department_name.")";?>

        </div>

        <input type="text" hidden id="employee_id" value="<?php echo  $compoff_data[0]->employee_id;?>">
        <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_details">
                <thead>
                <tr>
                    <th>Action</th>
                    <th>Employee Id</th>
                    <th>No of Days</th>
                    <th>Remarks</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Added By</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
    <script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.css">
    <link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css">
    <script type="text/javascript" src="<?php echo base_url();?>skin/vendor/ion.rangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
