<?php
/* Leave Detail view
*/
?>
<?php $session = $this->session->userdata('username');
$role_resources_ids = $this->Xin_model->user_role_resource();
?>
<?php $user = $this->Xin_model->read_user_info($session['user_id']);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    <h4 class="modal-title" id="edit-modal-data">View Request</h4>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-block bg-white">
            <h2><strong>Request Detail</strong></h2>
            <table class="table table-striped m-md-b-0">
                <tbody>
                <tr>
                    <th scope="row">Employee</th>
                    <td class="text-right"><?php echo $first_name.' '.$last_name;?></td>
                </tr>
                <tr>
                    <th scope="row">Applied On</th>
                    <td class="text-right"><?php echo $this->Xin_model->set_date_format($created_at);?></td>
                </tr>
                </tbody>
            </table>
            <br>
            <div class="the-notes info"><?php echo htmlspecialchars_decode(stripslashes($description));;?></div>
        </div>
    </div>
    <?php if(in_array('32',$role_resources_ids)) { ?>
        <div class="col-md-12">
            <div class="box box-block bg-white">
                <h2><strong>Update Status</strong></h2>
                <form action="<?php echo site_url("requests/update_status").'/'.$request_id;?>/" method="post" name="update_status" id="update_status">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="Status">
                                    <option value="1" <?php if($status=='1'):?> selected <?php endif; ?>>Pending</option>
                                    <option value="2" <?php if($status=='2'):?> selected <?php endif; ?>>Approved</option>
                                    <option value="3" <?php if($status=='3'):?> selected <?php endif; ?>>Rejected</option>
                                    <option value="4" <?php if($status=='4'):?> selected <?php endif; ?>>Issued</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary save">Save</button>
                </form>
            </div>
        </div>
    <?php } ?>
</div>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/select2/dist/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/select2/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/vendor/ion.rangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // On page load: datatable
        var xin_table = $('#xin_table').dataTable({
            "bDestroy": true,
            "ajax": {
                url : "<?php echo site_url("requests/request_list") ?>",
                type : 'GET'
            },
            "fnDrawCallback": function(settings){
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        $('[data-plugin="select_hrm"]').select2({ width:'100%' });

        $('#description2').summernote({
            height: 67,
            minHeight: null,
            maxHeight: null,
            focus: false
        });
        $('.note-children-container').hide();

        /* Edit data */
        $("#update_status").submit(function(e){
            e.preventDefault();
            var obj = $(this), action = obj.attr('name');
            $('.save').prop('disabled', true);
            var description = $("#description2").code();
            $.ajax({
                type: "POST",
                url: e.target.action,
                data: obj.serialize()+"&is_ajax=1&edit_type=ticket&form="+action,
                cache: false,
                success: function (JSON) {
                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('.save').prop('disabled', false);
                    } else {
                        xin_table.api().ajax.reload(function(){
                            toastr.success(JSON.result);
                        }, true);
                        $('.view-modal-data').modal('toggle');
                        $('.save').prop('disabled', false);
                    }
                }
            });
        });
    });
</script>

