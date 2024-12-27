<?php
/* Employee Exit view
*/
?>
<?php $session = $this->session->userdata('username');?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="box box-block bg-white">
    <div class=row">
        <div class="col-md-12">
            <h2><strong>List All</strong> Liabilities

        </div>
        <div class="position-relative d-flex align-items-center">
            <label class="required fs-7 fw-bold mb-2">Select a Date Range </label>
            <input class="form-control form-control-success" id="reportrange" type="text" name="daterange" value="" />
            <!--end::Datepicker-->
        </div>
    </div>        <!--begin::Input-->


   <br>
    <br>
        <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="liability_table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Salary</th>
                <th>Loan</th>
                <th>Advance Salary </th>
                <th>Expenses</th>
                <th>Gratuity</th>
                <th>Total</th>
                           </tr>
            </thead>
        </table>
    </div>
        </div>

</div>
