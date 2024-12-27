<?php
/*
* Tickets view
*/
$session = $this->session->userdata('username');
?>

<link rel="stylesheet" href="<?php echo site_url(); ?>skin/document/glrstyle.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo site_url(); ?>skin/document/jquery.form.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


<br>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#home">Leave Requests</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#menu1">Annual Leave Requests</a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div id="home" class="tab-pane active"><br>
        <div class="box box-block bg-white">
            <h2><strong>List All</strong> Leave Requests

            </h2>
            <div class="table-responsive" data-pattern="priority-columns">
                <table class="table table-striped table-bordered dataTable" id="leave_list">
                    <thead>
                    <tr>
                        <!--                <th>Action</th>-->
                        <th>Action</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Request Duration</th>
                        <th>Applied On</th>
                        <th>Reason</th>
                        <th>Approval Status</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
    <div id="menu1" class="tab-pane fade"><br>
        <div class="box box-block bg-white">
            <h2><strong>List All</strong> Annual Leave Requests
                            </h2>
            <div class="table-responsive" data-pattern="priority-columns">
                <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
                    <thead>
                    <tr>
                        <th>Action</th>
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


    </div>

</div>
