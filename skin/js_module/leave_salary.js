$(document).ready(function() {


    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[6, "desc"]] ,

        "ajax": {
            url : site_url+"leave_salary/leave_salary_list/",
            type : 'GET'
        },
        "fnDrawCallback": function(settings){
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    $('.edit-modal-data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var leave_id = button.data('leave_id');
        var modal = $(this);
        $.ajax({
            url : site_url+"leave_salary/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&data=leave&leave_id='+leave_id,
            success: function (response) {
                if(response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    });
    $('#employee-select').on('change', function() {
        var employeeId = $(this).val();
        var days = $('#days').val();
        $.ajax({
            url: base_url + "/check_salary/",
            type: 'POST',
            data: {employee_id: employeeId,days: days},
            success: function (JSON) {
                // Do something with the response, such as displaying the leave balance
                $('#salary').val("AED " + JSON.amount);
                $('#amount').val(JSON.amount);
                toastr.warning(JSON.result);
            },
            error: function () {
                toastr.error('Error occurred while fetching amount');
            }
        });
    });
    $('#days').on('keyup', function() {
        var employeeId = $('#employee-select').val();
        var days = $(this).val();
        if((employeeId!='')&&(employeeId!=undefined)) {
            $.ajax({
                url: base_url + "/check_salary/",
                type: 'POST',
                data: {employee_id: employeeId, days: days},
                success: function (JSON) {
                    // Do something with the response, such as displaying the leave balance
                    $('#salary').val("AED " + JSON.amount);
                    $('#amount').val(JSON.amount);
                },
                error: function () {
                    toastr.error('Error occurred while fetching amount');
                }
            });
        }
    });


    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width:'100%' });
    $('#remarks').summernote({
        height: 70,
        minHeight: null,
        maxHeight: null,
        focus: false
    });
    $('.note-children-container').hide();

    // Date
    $('.date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat:'yy-mm-dd',
        yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10),
    });

    /* Add data */ /*Form Submit*/
    $("#xin-form").submit(function(e){
        e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        var remarks = $("#remarks").code();
        $('.save').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=1&add_type=leave&form="+action+"&remarks="+remarks,
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
    $("#delete_record").submit(function(e){
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=2&type=delete&form="+action,
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
    $( document ).on( "click", ".delete", function() {
        $('input[name=_token]').val($(this).data('record-id'));
        $('#delete_record').attr('action',site_url+'leave_salary/delete_leave/'+$(this).data('record-id'));
    });
});