var department_id='';
$(document).ready(function() {
	$('#select-all').on('change', function() {
		// Get all checkboxes in the table
		var checkboxes = $('table').find('.row-checkbox');
		// Set the state of all checkboxes to match the state of the "Select All" checkbox
		checkboxes.prop('checked', $(this).is(':checked'));

	});

	var employee_id = jQuery('#employee_id').val();
	department_id = jQuery('#department_id').val();
	var month_year = jQuery('#month_year').val();
	var xin_table = $('#xin_table').dataTable({
		// "serverSide": true,
		"pageLength": 100,
		dom: 'Bfrltip',
		"order": [[1, "asc"]] ,
		select: {
			style: 'multi', // Allow multiple row selection
			// selector: 'td:first-child' // Allow selection when clicking on the first column
		},
		buttons: [

			{
				extend: 'copy',
				text: 'Copy',
				action: function(e, dt, button, config) {
					if (dt.rows({ selected: true }).any()) {
						config.exportOptions.rows = { selected: true };
					} else {
						config.exportOptions.rows = { selected: null };
					}
					$.fn.dataTable.ext.buttons.copyHtml5.action(e, dt, button, config);
				},
				exportOptions: {
					columns: ':visible:not(.no-export)',

				}
			},
			{
				extend: 'csv',
				text: 'CSV',
				action: function(e, dt, button, config) {
					if (dt.rows({ selected: true }).any()) {
						config.exportOptions.rows = { selected: true };
					} else {
						config.exportOptions.rows = { selected: null };
					}
					$.fn.dataTable.ext.buttons.csvHtml5.action(e, dt, button, config);
				},
				exportOptions: {
					columns: ':visible:not(.no-export)',

				}
			},

			{
				extend: 'excel',
				text: 'Excel',
				action: function(e, dt, button, config) {
					if (dt.rows({ selected: true }).any()) {
						config.exportOptions.rows = { selected: true };
					} else {
						config.exportOptions.rows = { selected: null };
					}
					$.fn.dataTable.ext.buttons.excelHtml5.action(e, dt, button, config);
				},
				exportOptions: {
					columns: ':visible:not(.no-export)',
				}
			},
			{
				extend: 'pdf',
				text: 'PDF',
				action: function(e, dt, button, config) {
					if (dt.rows({ selected: true }).any()) {
						config.exportOptions.rows = { selected: true };
					} else {
						config.exportOptions.rows = { selected: null };
					}
					$.fn.dataTable.ext.buttons.pdfHtml5.action(e, dt, button, config);
				},
				exportOptions: {
					columns: ':visible:not(.no-export)',

				},
				title: 'EMSO- Salary Report'+month_year,
				orientation:'landscape',

				// customize : function(doc){
				// 	var colCount = new Array();
				// 	$(xin_table).find('tbody tr:first-child td').each(function(){
				// 		if($(this).attr('colspan')){
				// 			for(var i=1;i<=$(this).attr('colspan');$i++){
				// 				colCount.push('*');
				// 			}
				// 		}else{
				// 			colCount.push('*');
				// 		}
				// 	});
				// 	doc.content[1].table.widths = colCount;
				// 	doc.content.unshift(
				// 		{ image: site_url + '/uploads/logo/emso-logo.png', width: 100, alignment: 'center' },
				// 		{ text: 'Emirates MotorSports Organization', alignment: 'center', margin: [0, 10] }
				// 	);
				// }
			},
			{
				extend: 'print',
				text: 'Print',
				action: function(e, dt, button, config) {
					if (dt.rows({ selected: true }).any()) {
						config.exportOptions.rows = { selected: true };
					} else {
						config.exportOptions.rows = { selected: null };
					}
					$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
				},
				exportOptions: {
					columns: ':visible:not(.no-export)'
				}
			},
		],
		columnDefs: [
			{
				targets: [0, 16, 17,14], // Columns to hide
				visible: false,
				className: 'no-export' // Apply the 'no-export' class
			}
		],

		"bDestroy": true,
		"ajax": {
			url : site_url+"payroll/payslip_list/?employee_id="+employee_id+"&month_year="+month_year+"&dept="+department_id,
			type : 'GET'
		},
		"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();
		},
		"columnDefs": [ {
			"targets": 0,
			"orderable": false
		},
			{
				"targets": 17, // Index of the column to hide
				"visible": false
			}
			]
	});

	$('#xin_table tbody').on('click', 'td', function() {
		var columnIndex = xin_table.api().cell(this).index().column;
		if (columnIndex === 17) { // 1 refers to the second column
			$(this).toggleClass('selected');
		}	});
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

// Month & Year
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
			$(this).datepicker('setDate', new Date(year, month, 1));
			$(this).datepicker('widget').removeClass('hide-calendar');
			$(this).datepicker('widget').hide();
		}

	});

// delete
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

// detail modal data payroll
	$('.payroll_template_modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var employee_id = button.data('employee_id');
		var date = button.data('date');
		var modal = $(this);
		$.ajax({
			url: site_url+'payroll/payroll_template_read/',
			type: "GET",
			data: 'jd=1&is_ajax=11&mode=not_paid&data=payroll_template&type=payroll_template&employee_id='+employee_id+'&date='+date,
			success: function (response) {
				if(response) {
					$("#ajax_modal_payroll").html(response);
				}
			}
		});
	});

// detail modal data  hourlywages
	$('.hourlywages_template_modal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var employee_id = button.data('employee_id');
		var modal = $(this);
		$.ajax({
			url: site_url+'payroll/hourlywage_template_read/',
			type: "GET",
			data: 'jd=1&is_ajax=11&mode=not_paid&data=hourlywages&type=hourlywages&employee_id='+employee_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal_hourlywages").html(response);
				}
			}
		});
	});
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var employee_id = button.data('employee_id');
		var pay_id = button.data('pay_id');
		var modal = $(this);
		$.ajax({
			url: site_url+'payroll/edit_payment_view/',
			type: "GET",
			data: 'jd=1&is_ajax=11&mode=modal&data=edit_payment&type=edit_payment&emp_id='+employee_id+'&pay_id='+pay_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
				}
			}
		});
	});
// detail modal data
	$('.detail_modal_data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var employee_id = button.data('employee_id');
		var pay_id = button.data('pay_id');
		var modal = $(this);
		$.ajax({
			url: site_url+'payroll/make_payment_view/',
			type: "GET",
			data: 'jd=1&is_ajax=11&mode=modal&data=pay_payment&type=pay_payment&emp_id='+employee_id+'&pay_id='+pay_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal_details").html(response);
				}
			}
		});
	});


// detail modal data
	$('.emo_monthly_pay').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var employee_id = button.data('employee_id');
		var date = button.data('date');
		var payment_date = $('#month_year').val();
		var modal = $(this);
		$.ajax({
			url: site_url+'payroll/pay_monthly/',
			type: "GET",
			data: 'jd=1&is_ajax=11&data=payment&type=monthly_payment&employee_id='+employee_id+'&date='+date+'&pay_date='+payment_date,
			success: function (response) {
				if(response) {
					$("#emo_monthly_pay_aj").html(response);
				}
			}
		});
	});

	$('.emo_hourly_pay').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var employee_id = button.data('employee_id');
		var payment_date = $('#month_year').val();
		var modal = $(this);
		$.ajax({
			url: site_url+'payroll/pay_hourly/',
			type: "GET",
			data: 'jd=1&is_ajax=11&data=payment&type=hourly_payment&employee_id='+employee_id+'&pay_date='+payment_date,
			success: function (response) {
				if(response) {
					$("#emo_hourly_pay_aj").html(response);
				}
			}
		});
	});

	/* Add data */ /*Form Submit*/
	$("#user_salary_template").submit(function(e){
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&edit_type=payroll&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table.api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				}
			}
		});
	});

	/* Set Salary Details*/
	$("#set_salary_details").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		var employee_id = jQuery('#employee_id').val();
		department_id = jQuery('#department_id').val();

		var month_year = jQuery('#month_year').val();

		var monthNames = ["January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December"
		];

		var d=new Date(month_year);
		var dd=d.getDate();
		var mm=monthNames[d.getMonth()];
		var yy=d.getFullYear();
		$('#p_month').html(mm+' '+yy);
// On page load: datatable
		var xin_table2 = $('#xin_table').DataTable({
			// "serverSide": true,
			"pageLength": 100,
			dom: 'Bfrltip',
			"order": [[1, "asc"]] ,
			select: {
				style: 'multi', // Allow multiple row selection
				// selector: 'td:first-child' // Allow selection when clicking on the first column
			},
			buttons: [

				{
					extend: 'copy',
					text: 'Copy',
					action: function(e, dt, button, config) {
						if (dt.rows({ selected: true }).any()) {
							config.exportOptions.rows = { selected: true };
						} else {
							config.exportOptions.rows = { selected: null };
						}
						$.fn.dataTable.ext.buttons.copyHtml5.action(e, dt, button, config);
					},
					exportOptions: {
						columns: ':visible:not(.no-export)',

					}
				},
				{
					extend: 'csv',
					text: 'CSV',
					action: function(e, dt, button, config) {
						if (dt.rows({ selected: true }).any()) {
							config.exportOptions.rows = { selected: true };
						} else {
							config.exportOptions.rows = { selected: null };
						}
						$.fn.dataTable.ext.buttons.csvHtml5.action(e, dt, button, config);
					},
					exportOptions: {
						columns: ':visible:not(.no-export)',

					}
				},

				{
					extend: 'excel',
					text: 'Excel',
					action: function(e, dt, button, config) {
						if (dt.rows({ selected: true }).any()) {
							config.exportOptions.rows = { selected: true };
						} else {
							config.exportOptions.rows = { selected: null };
						}
						$.fn.dataTable.ext.buttons.excelHtml5.action(e, dt, button, config);
					},
					exportOptions: {
						columns: ':visible:not(.no-export)',
					}
				},
				{
					extend: 'pdf',
					text: 'PDF',
					action: function(e, dt, button, config) {
						if (dt.rows({ selected: true }).any()) {
							config.exportOptions.rows = { selected: true };
						} else {
							config.exportOptions.rows = { selected: null };
						}
						$.fn.dataTable.ext.buttons.pdfHtml5.action(e, dt, button, config);
					},
					exportOptions: {
						columns: ':visible:not(.no-export)',

					},
					title: 'Salary Report- EMSO '+month_year,
					// customize: function (doc) {
					// 	// Add custom lines to the top of the PDF
					// 	doc.content.unshift(
					// 		{ text: '<img src="'+site_url+'/uploads/logo/emso-logo.png" alt="Logo">\n', margin: [0, 0, 0, 10] },
					// 		{ text: 'EMSO', margin: [0, 0, 0, 10] }
					// 	);
					//
					// }
				},
				{
					extend: 'print',
					text: 'Print',
					title: 'EMSO -Payroll List for'+month_year,

					action: function(e, dt, button, config) {
						if (dt.rows({ selected: true }).any()) {
							config.exportOptions.rows = { selected: true };
						} else {
							config.exportOptions.rows = { selected: null };
						}
						$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
					},
					exportOptions: {
						columns: ':visible:not(.no-export)'
					}
				},
			],
			columnDefs: [
				{
					targets: [0, 9, 11], // Columns to hide
					visible: false,
					className: 'no-export' // Apply the 'no-export' class
				}
			],
			"bDestroy": true,
			"ajax": {
				url : site_url+"payroll/payslip_list/?employee_id="+employee_id+"&month_year="+month_year+"&dept="+department_id,
				type : 'GET'
			},
			"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();
			},
			"columnDefs": [ {
				"targets": 0,
				"orderable": false
			},
				{
					"targets": 17, // Index of the column to hide
					"visible": false
				}
			]
		});
		// xin_table2.api().ajax.reload(function(){
		// }, true);
	});
	$('#generateBtn').click(function() {
		// Get all selected checkboxes
		var selected = $("input:checkbox:checked").map(function() {
			return this.value;
		}).get();
		if (selected.length === 0) {
			toastr.error("Please select at least one employee.");
		}else {

			// Show loader
			// $('#loader').show();
			var payment_date = $('#month_year').val();

			// Call AJAX function
			$.ajax({
				url: site_url+'payroll/generate_bulk_payslips/',
				type: 'post',
				data: {employees: selected,pay_date:payment_date,date:payment_date},
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
					} else {
						xin_table.api().ajax.reload(function(){
							toastr.success(JSON.result);
						}, true);
						xin_table2.api().ajax.reload(function(){
						}, true);

					}

				},
				error: function () {
					toastr.error('Error occurred while generating payslips.');
				},
				complete: function () {
					// Hide loader
					$('#loader').hide();
				}
			});
		}
	});
	$('#downloadbtn').click(function() {
		var payment_date = $('#month_year').val();

		var selectedCells = xin_table.api().cells('.selected', 17);

		var selectedValues = selectedCells.data().toArray();
		if (selectedValues.length === 0) {
			$.ajax({
				url: site_url+'payroll/generateTextFile/',
				type: 'POST',
				data: {pay_date:payment_date,employees:''},

				success: function(response) {
					// File download logic
					var downloadLink = document.createElement('a');
					downloadLink.href = response.fileurl;
					downloadLink.download = response.filename;
					downloadLink.click();
				}
			});
		}else{
			$.ajax({
				url: site_url+'payroll/generateTextFile/',
				type: 'POST',
				data: {pay_date:payment_date,employees:selectedValues},

				success: function(response) {
					// File download logic
					var downloadLink = document.createElement('a');
					downloadLink.href = response.fileurl;
					downloadLink.download = response.filename;
					downloadLink.click();
				}
			});
		}



	});
	$("#emailbtn").on("click", function() {
		$('#emailbtn').prop('disabled', true);

		// Show the confirmation modal
		$("#confirmationModal").modal("show");
	});

	// Handle the "Send Emails" button click inside the modal
	$("#confirmSendEmails").on("click", function() {
		var payment_date = $('#month_year').val();

		// Make an AJAX call to send the emails
		$.ajax({
			url: site_url+"payroll/send_emails", // Replace with the URL of your PHP file to handle the AJAX request
			type: 'POST',
			data: {pay_date:payment_date},
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
				} else {

					toastr.success(JSON.result);
				}
			}
		});
		$('#emailbtn').prop('disabled', false);

		// Hide the confirmation modal after the AJAX call is made
		$("#confirmationModal").modal("hide");
	});
});

	$( document ).on( "click", ".delete", function() {
		$('input[name=_token]').val($(this).data('record-id'));
		$('#delete_record').attr('action',base_url+'/delete_payslip/'+$(this).data('record-id'));
	});