var minDate, maxDate,startDate,endDate;





$(document).ready(function() {
    $(function () {

        var start = moment().startOf('year');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }


        $('#reportrange').daterangepicker({

            startDate: start,
            endDate: end,
            ranges: {
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            }
        }, cb);


        cb(start, end);

    });
    minDate = new Date(moment().startOf('year').format('YYYY-MM-DD'));
    maxDate = new Date(moment().format('YYYY-MM-DD'));

    // Custom filtering function which will search data in column four between two values
    $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
        minDate = new Date(picker.startDate.format('YYYY-MM-DD'));
        maxDate = new Date(picker.endDate.format('YYYY-MM-DD'));
         startDate = minDate.toISOString().split('T')[0];
         endDate = maxDate.toISOString().split('T')[0];

        console.log(startDate); // Output: "2023-01-01"
        console.log(endDate);
        tbl_liability.api().ajax.reload();

    });
    var tbl_liability = $('#liability_table').dataTable({
        processing: true,
        searching: true,
        bDestroy: true,
        paging: true,
        serverSide: false,
        bProcessing: true,
        dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],

        lengthChange: true,
        ajax: {
            url: site_url + "/liability/get_liability_report/",
            data: function (d) {
                // Additional parameters to be sent to the server-side script
                d.min_date = startDate;
                d.max_date = endDate;
                // Add more parameters as needed
            },
            type: 'GET'
        },
    });

    });