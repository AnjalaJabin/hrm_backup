<?php
/* Announcement view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Job Candidates</h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th>Action</th>
          <th>Job Title</th>
          <th>Candidate Name</th>
          <th>Email</th>
          <th>Status</th>
          <th>Apply Date</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
