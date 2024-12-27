<?php
/* Performance Appraisals view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Performance Appraisals </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee</th>
          <th>Department</th>
          <th>Designation</th>
          <th>Appraisal Date</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
