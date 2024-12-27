$(document).ready(function() {
    $('.nav-link').on('click', function() {
        // Remove active class from all tab links
        $('.nav-link').removeClass('active');
        // Add active class to the clicked tab link
        $(this).addClass('active');
    });
    $('#employee-select').on('change', function() {
        var employeeId = $(this).val();
        var date = $('#gratuity_date').val();
        $.ajax({
            url : base_url+"/check_gratuity_balance/",
            type: 'POST',
            data: {employee_id: employeeId,date:date},
            success: function(response) {
                // Do something with the response, such as displaying the leave balance
                $('#leave_balance').val("AED "+ response);
                $('#gratuity_balance').val(response);
            },
            error: function() {
                toastr.error('Error occurred while fetching gratuity balance');
            }
        });
        $.ajax({
            url : base_url+"/check_loan_balance/",
            type: 'POST',
            data: {employee_id: employeeId},
            success: function(response) {
                // Do something with the response, such as displaying the leave balance
                $('#loan_balance').val("AED "+ response);
            },
            error: function() {
                toastr.error('Error occurred while fetching loan balance');
            }
        });
    });
    $('#gratuity_date').on('change', function() {
        var date = $(this).val();
        var employeeId = $('#employee-select').val();
        if(employeeId) {
            $.ajax({
                url: base_url + "/check_gratuity_balance/",
                type: 'POST',
                data: {employee_id: employeeId,date:date},
                success: function (response) {
                    // Do something with the response, such as displaying the leave balance
                    $('#leave_balance').val("AED " + response);
                    $('#gratuity_balance').val(response);
                },
                error: function () {
                    toastr.error('Error occurred while fetching gratuity balance');
                }
            });
            $.ajax({
                url: base_url + "/check_loan_balance/",
                type: 'POST',
                data: {employee_id: employeeId},
                success: function (response) {
                    // Do something with the response, such as displaying the leave balance
                    $('#loan_balance').val("AED " + response);
                },
                error: function () {
                    toastr.error('Error occurred while fetching loan balance');
                }
            });
        }
    });
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[1, "asc"]] ,

        "ajax": {
            url : base_url+"/gratuity_list/",
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
        "order": [[1, "asc"]] ,

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
            data: obj.serialize()+"&is_ajax=1&add_type=gratuity&form="+action+"&description="+description,
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