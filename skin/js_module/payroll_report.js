var minDate, maxDate,startDate,endDate,department_id,employee_id,month_year;
$(document).ready(function() {
    minDate = new Date(moment().startOf('year').format('YYYY-MM-DD'));
    maxDate = new Date(moment().format('YYYY-MM-DD'));
    startDate = minDate.toISOString().split('T')[0];
    endDate = maxDate.toISOString().split('T')[0];
console.log(startDate);
    employee_id = jQuery('#employee_id').val();
    department_id = jQuery('#department_id').val();
    month_year = jQuery('#month_year').val();

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width:'100%' });
    $(function () {

        var start = moment().startOf('year');
        var end = moment();

        function cb(start, end) {
            $('#reportrange1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }


        $('#reportrange1').daterangepicker({

            startDate: start,
            endDate: end,
            showDropdowns: true, // Enables year and month dropdowns

            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]



            }
        }, cb);


        cb(start, end);

    });

    // Custom filtering function which will search data in column four between two values
    $('#reportrange1').on('apply.daterangepicker', function (ev, picker) {
        minDate = new Date(picker.startDate.format('YYYY-MM-DD'));
        maxDate = new Date(picker.endDate.format('YYYY-MM-DD'));
        startDate = minDate.toISOString().split('T')[0];
        endDate = maxDate.toISOString().split('T')[0];

        console.log(startDate); // Output: "2023-01-01"
        console.log(endDate);
        xin_table_report.api().ajax.reload();

    });
    var xin_table_report = $('#xin_table_report').dataTable({
        processing: true,
        searching: true,
        bDestroy: true,
        serverSide: false,
        bProcessing: true,
        paging: true,
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],

        ajax: {
            url: site_url + "payroll/payroll_report_list/",
            data: function (d) {
                // Additional parameters to be sent to the server-side script
                d.employee_id = employee_id;
                d.month_year = month_year;
                d.dept = department_id;
                d.min_date = startDate;
                d.max_date = endDate;

                // Add more parameters as needed
            },
            type: 'GET',
            error: function(xhr, textStatus, errorThrown) {
                console.error("AJAX Error: ", textStatus, errorThrown);
            }
        },
    });
    $('.month_year').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat:'M yy',
        yearRange: "-1:+1",
        beforeShow: function(input) {
            $(input).datepicker("widget").addClass('hide-calendar');
        },
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();

            // Update the month_year variable
            var month_year = year + '-' + (parseInt(month) + 1); // Adding 1 to month because JavaScript months are 0-based

            // Set the updated month_year for the AJAX request
            xin_table_report.api().settings().ajax.data.month_year = month_year;

            // Trigger the table to redraw with the updated data
            xin_table_report.api().ajax.reload();

            $(this).datepicker('setDate', new Date(year, month, 1));
            $(this).datepicker('widget').removeClass('hide-calendar');
            $(this).datepicker('widget').hide();
        }

    });
    $('#employee_id').on('change', function() {
        employee_id = $(this).val();
        console.log(employee_id);
        xin_table_report.api().ajax.reload();
    });
    $('#department_id').on('change', function() {
        department_id = $('#department_id').val();
        console.log(department_id);
        xin_table_report.api().ajax.reload();
    });
 $('#month_year').on('change', function() {
        month_year = $('#month_year').val();
        console.log(month_year);
        xin_table_report.api().ajax.reload();
    });


});