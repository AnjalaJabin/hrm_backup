$(document).ready(function() {
    var emp_id = $('#employee_id').val();
    // On page load: datatable
    var xin_table_details = $('#xin_table_details').dataTable({
        "bDestroy": true,
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "ajax": {
            url: base_url + "/compoff_list_employee/?emp_id="+emp_id,
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    $('.edit-modal-data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var ticket_id = button.data('ticket_id');
        var modal = $(this);
        $.ajax({
            url: base_url + "/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&data=ticket&ticket_id=' + ticket_id,
            success: function (response) {
                if (response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({width: '100%'});

    $('#description2').summernote({
        height: 67,
        minHeight: null,
        maxHeight: null,
        focus: false
    });
    $('.note-children-container').hide();


    $("#delete_record").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=2&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                } else {
                    $('.delete-modal').modal('toggle');
                    xin_table_details.api().ajax.reload(function () {
                        toastr.success(JSON.result);
                    }, true);
                }
            }
        });
    });

    $(document).on("click", ".delete", function () {
        $('input[name=_token]').val($(this).data('record-id'));
        $('#delete_record').attr('action', base_url + '/delete/' + $(this).data('record-id'));
    });
});