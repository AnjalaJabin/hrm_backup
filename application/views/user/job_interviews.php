<?php
/* Job Interviews view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="box box-block bg-white">
  <h2><strong>List All</strong> Job Interviews </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table">
      <thead>
        <tr>
          <th>Action</th>
          <th>Job Post</th>
          <th>Message</th>
          <th>Interview Place</th>
          <th>Interview Date & Time</th>
          <th>Interviewers</th>
          <th>Added By</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
