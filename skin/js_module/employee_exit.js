
$(document).ready(function() {
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url : base_url+"/exit_list/",
			type : 'GET'
		},
		"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	$('#reason').summernote({
		height: 90,
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
		var exit_id = button.data('exit_id');
		var modal = $(this);
		$.ajax({
			url : base_url+"/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=exit&exit_id='+exit_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
				}
			}
		});
	});
	$('.view-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var exit_id = button.data('exit_id');
		var modal = $(this);
		$.ajax({
			url : base_url+"/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=view_exit&exit_id='+exit_id,
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
		var reason = $("#reason").code();
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=exit&form="+action+"&reason="+reason,
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
					$('.add-form').fadeOut('slow');
					$('#xin-form')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
				}
			}
		});
	});
	$('#exit_date').change(function() {
		var exit_date = $(this).val();
		var employeeId = jQuery('#employee_select').find("option:selected").val();

		if (!employeeId) {
			toastr.error('Please Select The Employee');
		} else {
			$.when(
				$.get(site_url + "endofservice/get_employee_data/" + employeeId),
				$.get(site_url + "endofservice/get_expenses/" + employeeId + "/" + exit_date),
				$.get(site_url + "endofservice/get_salary/" + employeeId + "/" + exit_date),
				$.ajax({
					url: site_url + "/gratuity/check_gratuity_balance/",
					type: 'POST',
					data: { employee_id: employeeId,date:exit_date }
				}),
				$.ajax({
					url: site_url + "/gratuity/check_loan_balance/",
					type: 'POST',
					data: { employee_id: employeeId }
				}),
				$.ajax({
					url: site_url + "employee/annual_leave/check_leave_balance/",
					type: 'POST',
					data: { employee_id: employeeId,date:exit_date }
				}),
				$.ajax({
					url: site_url + "/leave_salary/check_salary_on_exit/",
					type: 'POST',
					data: { employee_id: employeeId,date:exit_date }
				}),
				$.ajax({
					url: site_url + "flights/check_ticket_balance/",
					type: 'POST',
					data: { employee_id: employeeId,date:exit_date }
				})
			).done(function(data1, data2, data3, data4, data5, data6, data7, data8) {
				var parsedData = JSON.parse(data1[0]);
				$('#email').text(parsedData.email);
				$('#employee_name').text(parsedData.name);
				$('#designation').text(parsedData.designation);
				$('#department').text(parsedData.department);
				$('#employee_id').text(parsedData.employee_id);
				$('#doj').text(parsedData.doj);

				$('#expenses').val(data2[0]);
				$('#pending_salary').val(data3[0]);
				$('#gratuity_amount').val(data4[0]);
				$('#loan_balance').val(data5[0]);
				$('#leave_balance').val(data6[0] + "/30");
				$('#leave_salary').val(data7[0].amount);

				var leaveBalance = parseFloat(data8[0]);
				$('#ticket_balance').val(leaveBalance.toFixed(5));


				net_calc();
			}).fail(function() {
				toastr.error('Error occurred while fetching data');
			});
		}
	});
	$("#employee_select").change(function() {
		var employeeId = $(this).val();
		var exit_date = $('#exit_date').val();

		$.when(
			$.get(site_url + "endofservice/get_employee_data/" + employeeId),
			$.get(site_url + "endofservice/get_expenses/" + employeeId + "/" + exit_date),
			$.get(site_url + "endofservice/get_salary/" + employeeId + "/" + exit_date),
			$.ajax({
				url: site_url + "/gratuity/check_gratuity_balance/",
				type: 'POST',
				data: { employee_id: employeeId,date:exit_date }
			}),
			$.ajax({
				url: site_url + "/gratuity/check_loan_balance/",
				type: 'POST',
				data: { employee_id: employeeId }
			}),
			$.ajax({
				url: site_url + "employee/annual_leave/check_leave_balance/",
				type: 'POST',
				data: { employee_id: employeeId,date:exit_date }
			}),
			$.ajax({
				url: site_url + "/leave_salary/check_salary_on_exit/",
				type: 'POST',
				data: { employee_id: employeeId,date:exit_date }
			}),
			$.ajax({
				url: site_url + "flights/check_ticket_balance/",
				type: 'POST',
				data: { employee_id: employeeId,date:exit_date }
			})
		).done(function(data1, data2, data3, data4, data5, data6, data7, data8) {
			var parsedData = JSON.parse(data1[0]);
			$('#email').text(parsedData.email);
			$('#employee_name').text(parsedData.name);
			$('#designation').text(parsedData.designation);
			$('#department').text(parsedData.department);
			$('#employee_id').text(parsedData.employee_id);
			$('#doj').text(parsedData.doj);

			$('#expenses').val(data2[0]);
			$('#pending_salary').val(data3[0]);
			$('#gratuity_amount').val(data4[0]);
			// console.log("ab" + $('#gratuity_amount').val());
			$('#loan_balance').val(data5[0]);
			$('#leave_balance').val(data6[0] + "/30");
			$('#leave_salary').val(data7[0].amount);

			var leaveBalance = parseFloat(data8[0]);
			$('#ticket_balance').val(leaveBalance.toFixed(5));


			net_calc();
		}).fail(function() {
			toastr.error('Error occurred while fetching data');
		});
	});
	$('#leave_salary').keyup(function (){
		net_calc();
	});
	$('#ticket_amount').keyup(function (){
		net_calc();
	});
	$('#pending_salary').keyup(function (){
		net_calc();
	});
	$('#gratuity_amount').keyup(function (){
		net_calc();
	});
	$('#overtime').keyup(function (){
		net_calc();
	});
	$('#expenses').keyup(function (){
		net_calc();
	});
	$('#loan_balance').keyup(function (){
		net_calc();
	});
	$('#other_deductions').keyup(function (){
		net_calc();
	});
	function net_calc(){
		var leave_salary = $('#leave_salary').val();
		var loan = $('#loan_balance').val();
		var ticket_amount = $('#ticket_amount').val();

		var gratuity_amount = $('#gratuity_amount').val();
		// console.log("abcde"+$('#ticket_amount').val());

		var pending_salary = $('#pending_salary').val();

		var expenses = $('#expenses').val();
		var overtime = $('#overtime').val();
		var extra_deductions = $('#other_deductions').val();

		if(leave_salary>0){  }else{ leave_salary = 0; }
		if(expenses>0){  }else{ expenses = 0; }
		if(overtime>0){  }else{ overtime = 0; }
		if(extra_deductions>0){  }else{ extra_deductions = 0; }

		if(gratuity_amount>0){  }else{ gratuity_amount = 0; }
		if(ticket_amount>0){  }else{ ticket_amount = 0; }
		if(pending_salary>0){  }else{ pending_salary = 0; }
		if(leave_salary>0){  }else{ leave_salary = 0; }
		if(loan>0){  }else{ loan = 0; }

		var total_net = parseFloat(gratuity_amount);
		total_net=total_net+parseFloat(leave_salary);
		total_net=total_net+parseFloat(pending_salary);
		total_net=total_net-parseFloat(loan);
		total_net=total_net-parseFloat(extra_deductions);
		total_net=total_net+parseFloat(expenses);
		total_net=total_net+parseFloat(overtime);
		total_net=total_net+parseFloat(ticket_amount);
		var net = total_net.toFixed(2);
		$('#net_amount').val(net);
		$('#net_sal').html("Total Net: "+net);

	}

});

$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
});
