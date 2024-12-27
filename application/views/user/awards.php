<?php
/* Awards view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Awards </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Award Name</th>
          <th>Gift</th>
          <th>Cash Price</th>
          <th>Month & Year</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
