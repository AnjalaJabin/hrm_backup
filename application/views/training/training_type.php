<?php
/* Training Type view
*/
?>
<?php $session = $this->session->userdata('username');?>

<div class="row m-b-1 animated fadeInRight">
  <div class="col-md-4">
    <div class="box box-block bg-white">
      <h2><strong>Add New</strong> Type</h2>
      <form class="m-b-1 add" method="post" action="<?php echo site_url("training_type/add_type") ?>" name="add_type" id="xin-form">
        <div class="form-group">
          <label for="type_name">Training Type</label>
          <input type="text" class="form-control" name="type_name" placeholder="Enter Training Type">
        </div>
        <button type="submit" class="btn btn-primary save">Save</button>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-block bg-white">
      <h2><strong>List All</strong> Training Types</h2>
      <div class="table-responsive" data-pattern="priority-columns">
        <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
          <thead>
            <tr>
              <th>Action</th>
              <th>ID</th>
              <th>NAME</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- responsive --> 
    </div>
  </div>
</div>
