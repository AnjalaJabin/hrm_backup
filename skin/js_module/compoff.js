$(document).ready(function() {
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[1, "asc"]] ,

        "ajax": {
            url : base_url+"/compoff_list/",
            type : 'GET'
        },
        "fnDrawCallback": function(settings){
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    var xin_table_employee_list = $('#xin_table_employee_list').dataTable({
        "bDestroy": true,
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "ajax": {
            url : base_url+"/gratuity_list_employees/",
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

    // edit
    // $('.view-modal-data').on('show.bs.modal', function (event) {
    //     var button = $(event.relatedTarget);
    //     var ticket_id = button.data('ticket_id');
    //     var modal = $(this);
    //     $.ajax({
    //         url : base_url+"/read_entry/",
    //         type: "GET",
    //         data: 'jd=1&is_ajax=1&mode=modal&data=ticket&ticket_id='+ticket_id,
    //         success: function (response) {
    //             if(response) {
    //                 $("#ajax_modal_view").html(response);
    //             }
    //         }
    //     });
    // });

    /* Add data */ /*Form Submit*/
    $("#xin-form").submit(function(e){
        e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $('.save').prop('disabled', true);
        var description = $("#description").code();
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=1&add_type=compoff&form="+action+"&description="+description,
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
