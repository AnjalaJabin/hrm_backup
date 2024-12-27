$(document).ready(function() {

    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url : base_url+"/request_list/?my_request="+my_request,
            type : 'GET'
        },
        "fnDrawCallback": function(settings){
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width:'100%' });


    $('#description').summernote({
        height: 67,
        minHeight: null,
        maxHeight: null,
        focus: false
    });
    $('.note-children-container').hide();
    /* Delete data */
    $("#delete_record").submit(function(e){
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=2&form="+action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                } else {
                    $('.delete-modal').modal('toggle');
                    xin_table.api().ajax.reload(function(){
                        toastr.success(JSON.result);
                    }, true);
                }
            }
        });
    });

    // edit
    $('.edit-modal-data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var ticket_id = button.data('ticket_id');
        var modal = $(this);
        $.ajax({
            url : base_url+"/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&data=ticket&my_request='+my_request+'&ticket_id='+ticket_id,
            success: function (response) {
                if(response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    });
    $('.view-modal-data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var travel_id = button.data('request_id');
        var modal = $(this);
        $.ajax({
            url : site_url+"requests/read_view/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=view_modal&data=view_request&request_id='+travel_id,
            success: function (response) {
                if(response) {
                    $("#ajax_modal_view").html(response);
                }
            }
        });
    });
    /* Add data */ /*Form Submit*/
    $("#xin-form").submit(function(e){
        e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $('.save').prop('disabled', true);
        var description = $("#description").code();
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=1&add_type=request&form="+action+"&description="+description,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('.save').prop('disabled', false);
                } else {
                    xin_table.api().ajax.reload(function(){
                        toastr.success(JSON.result);
                    }, true);
                    $('.add-form').fadeOut('slow');
                    $('#xin-form')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            }
        });
    });
});
$( document ).on( "click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
});