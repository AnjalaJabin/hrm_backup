<?php
/* Worksheets view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Worksheets </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th>Title</th>
          <th>End Date</th>
          <th>Status</th>
          <th>Assigned To</th>
          <th>Created By</th>
          <th>Progress</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- responsive --> 
</div>
