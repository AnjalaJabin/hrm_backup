$(document).ready(function() {
        $('#employee-select').on('change', function() {
            var employeeId = $(this).val();        console.log("kke");
        $.ajax({
            url : base_url+"/check_ticket_balance/",
            type: 'POST',
            data: {employee_id: employeeId},
            success: function(response) {
                // Do something with the response, such as displaying the leave balance
                var leaveBalance = parseFloat(response);
                $('#leave_balance').val(leaveBalance.toFixed(5));
            },
            error: function() {
                alert('Error occurred while fetching leave balance');
            }
        });
    });
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],

        "ajax": {
            url : base_url+"/ticket_list/",
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
            data: 'jd=1&is_ajax=1&mode=modal&data=ticket&ticket_id='+ticket_id,
            success: function (response) {
                if(response) {
                    $("#ajax_modal").html(response);
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
            data: obj.serialize()+"&is_ajax=1&add_type=ticket&form="+action+"&description="+description,
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