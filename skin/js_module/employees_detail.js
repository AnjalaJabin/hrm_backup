$(document).ready(function(){

	// get data
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var field_tpe = button.data('field_type');
		if(field_tpe == 'contact'){
			var field_add = '&data=emp_contact&type=emp_contact&';
		} else if(field_tpe == 'document'){
			var field_add = '&data=emp_document&type=emp_document&';
		} else if(field_tpe == 'qualification'){
			var field_add = '&data=emp_qualification&type=emp_qualification&';
		} else if(field_tpe == 'work_experience'){
			var field_add = '&data=emp_work_experience&type=emp_work_experience&';
		} else if(field_tpe == 'bank_account'){
			var field_add = '&data=emp_bank_account&type=emp_bank_account&';
		} else if(field_tpe == 'contract'){
			var field_add = '&data=emp_contract&type=emp_contract&';
		} else if(field_tpe == 'leave'){
			var field_add = '&data=emp_leave&type=emp_leave&';
		} else if(field_tpe == 'flight'){
			var field_add = '&data=ticket&type=ticket&';
		}  else if(field_tpe == 'location'){
			var field_add = '&data=emp_location&type=emp_location&';
		} else if(field_tpe == 'imgdocument'){
			var field_add = '&data=e_imgdocument&type=e_imgdocument&';
		}else if(field_tpe == 'annual_leave'){
			var field_add = '&data=annual_leave&type=annual_leave&';
		}
		var modal = $(this);
		$.ajax({
			url: site_url+'employees/dialog_'+field_tpe+'/',
			type: "GET",
			data: 'jd=1'+field_add+'field_id='+field_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
				}
			}
		});
   });
   
   // view
	$('.view-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var document_id = button.data('field_id');
		var modal = $(this);
	$.ajax({
		url : base_url+"/read_document/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=view_document&document_id='+document_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal_view").html(response);
			}
		}
		});
	});
   
	/* Update basic info */
	$("#basic_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&data=basic_info&type=basic_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				}
			}
		});
	});
	
	/* Update profile picture */
	$("#f_profile_picture").submit(function(e){
		var fd = new FormData(this);
		var user_id = $('#user_id').val();
		var session_id = $('#session_id').val();
		$('.icon-spinner3').show();
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 2);
		fd.append("type", 'profile_picture');
		fd.append("data", 'profile_picture');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					toastr.success(JSON.result);
					$('.icon-spinner3').hide();
					$('#remove_file').show();
					$("#remove_profile_picture").attr('checked', false);
					$('#u_file').attr("src", JSON.img);
					if(user_id == session_id){
						$('.user_avatar').attr("src", JSON.img);
					}
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.icon-spinner3').hide();
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	
	/* Update social networking */
	$("#f_social_networking").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&data=social_info&type=social_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				}
			}
		});
	});

	 // get departments
	  jQuery("#aj_department").change(function(){
		jQuery.get(site_url+"employees/designation/"+jQuery(this).val(), function(data, status){
			jQuery('#designation_ajax').html(data);
		});
		  jQuery.get(base_url+"/reportingdesignation/"+jQuery(this).val(), function(data, status){
			  jQuery('#reporting_ajax').html(data);
		  });

	  });

	$(".nav-tabs-link").click(function(){
		var profile_id = $(this).data('profile');
		var profile_block = $(this).data('profile-block');
		$('.nav-item-link').removeClass('active-link');
		$('.current-tab').hide();
		$('#'+profile_block).show();
		$('#user_details_'+profile_id).addClass('active-link');
	});
	$(document).on( "click", "#des_add_btn", function() {
		$('#des_add_div').show();
		$('#des_add_btn').hide();
	});

	$(document).on( "click", "#des_close_btn", function() {
		$('#des_add_div').hide();
		$('#des_add_btn').show();
	});

	$(document).on( "click", "#des_sub_btn", function() {
		var des_val = $('#des_add_val').val();
		if(des_val==='')
		{
			toastr.error('Type designation');
		}
		else
		{

			var department_id    = $('#aj_department').val();
			var designation_name = $('#des_add_val').val();
			$.ajax({
				type: "POST",
				url: base_url+'/add_designation/',
				data: { department_id:department_id, designation_name:designation_name, is_ajax:'1', add_type:'designation'},
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						toastr.error(JSON.error);
					} else {
						toastr.success(JSON.result);
						$('#des_add_div').hide();
						$('#des_add_btn').show();
					}
				}
			});

			jQuery.get(base_url+"/designation/"+department_id, function(data, status){
				jQuery('#designation_ajax').html(data);
			});

		}
	});
	// On page load: table_contacts
	 var xin_table_contact = $('#xin_table_contact').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/contacts/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	// On page load > documents
	var xin_table_immigration = $('#xin_table_imgdocument').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/immigration/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	// On page load > documents
	var xin_table_document = $('#xin_table_document').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/documents/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	// On page load > qualification
	var xin_table_qualification = $('#xin_table_qualification').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/qualification/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	// On page load 
	var xin_table_work_experience = $('#xin_table_work_experience').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/experience/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	// On page load 
	var xin_table_bank_account = $('#xin_table_bank_account').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/bank_account/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	// On page load > contract
	var xin_table_contract = $('#xin_table_contract').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/contract/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
    
    
    var xin_table_salary = $('#xin_table_salary').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/salary/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
    
	
	// On page load > leave
	var xin_table_leave = $('#xin_table_leave').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/leave/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	var xin_table_annual_leave = $('#xin_table_annual_leave').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/annual_leave_user/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();
		}
    });

	var xin_table_flight = $('#xin_table_flight').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/employee_ticket_list/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();
		}
    });

	// On page load
	var xin_table_shift = $('#xin_table_shift').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/shift/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	// On page load 
	var xin_table_location = $('#xin_table_location').dataTable({
        "bDestroy": true,
		"ajax": {
            url : site_url+"employees/location/"+$('#user_id').val(),
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	/* Add contact info */
	jQuery("#contact_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=4&data=contact_info&type=contact_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_contact.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					jQuery('#contact_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	/* Add contact info */
	jQuery("#contact_info2").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save2').prop('disabled', true);
		$('.icon-spinner33').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=4&data=contact_info&type=contact_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner33').hide();
					jQuery('.save2').prop('disabled', false);
				} else {
					toastr.success(JSON.result);
					$('.icon-spinner33').hide();
					jQuery('.save2').prop('disabled', false);
				}
			}
		});
	});
	
	/* Add document info */
	$("#document_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 7);
		fd.append("type", 'document_info');
		fd.append("data", 'document_info');
		fd.append("form", action);
		e.preventDefault();
		$('.icon-spinner3').show();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_document.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					$('#output').html('');
					jQuery('#document_info')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	
	/* Add document info */
	$("#immigration_info").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 7);
		fd.append("type", 'immigration_info');
		fd.append("data", 'immigration_info');
		fd.append("form", action);
		e.preventDefault();
		$('.icon-spinner3').show();
		$('.save').prop('disabled', true);
		$.ajax({
			url: e.target.action,
			type: "POST",
			data:  fd,
			contentType: false,
			cache: false,
			processData:false,
			success: function(JSON)
			{
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_immigration.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#document_info')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});
	
	/* Add qualification info */
	jQuery("#qualification_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=10&data=qualification_info&type=qualification_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_qualification.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					jQuery('#qualification_info')[0].reset(); // To reset form fields
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	/* Add work experience info */
	jQuery("#work_experience_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=13&data=work_experience_info&type=work_experience_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_work_experience.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#work_experience_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	/* Add bank account info */
	jQuery("#bank_account_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=16&data=bank_account_info&type=bank_account_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_bank_account.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					jQuery('#bank_account_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	/* Add contract info */
	jQuery("#contract_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=19&data=contract_info&type=contract_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_contract.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					jQuery('#contract_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	/* Add leave info */
	jQuery("#leave_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=22&data=leave_info&type=leave_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_leave.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					jQuery('#leave_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	/* Add leave info */
	jQuery("#add_leave_form").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=22&data=leave_info&add_type=leave&form="+action,
			cache: false,
			success: function (JSON) {
				console.log(JSON);
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_annual_leave.api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					jQuery('#add_leave')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#add_ticket").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=ticket&form="+action+"&description="+description,
			cache: false,
			success: function (JSON) {
				console.log(JSON);
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_flight.api().ajax.reload(function(){
						toastr.success(JSON.result);
					}, true);
					jQuery('#add_ticket')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	/* Add shift info */
	jQuery("#shift_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=25&data=shift_info&type=shift_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_shift.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					jQuery('#shift_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	/* Add location info */
	jQuery("#location_info").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=28&data=location_info&type=location_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_location.api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					jQuery('#location_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	
	/* Add change password */
	jQuery("#e_change_password").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = jQuery(this), action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=31&data=e_change_password&type=change_password&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					toastr.success(JSON.result);
					$('.icon-spinner3').hide();
					jQuery('#e_change_password')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
		
	
   
   /* Delete data */
	$("#delete_record").submit(function(e){
	var tk_type = $('#token_type').val();
	if(tk_type == 'contact'){
		var field_add = '&is_ajax=6&data=delete_record&type=delete_contact&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'document'){
		var field_add = '&is_ajax=8&data=delete_record&type=delete_document&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'qualification'){
		var field_add = '&is_ajax=12&data=delete_record&type=delete_qualification&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'work_experience'){
		var field_add = '&is_ajax=15&data=delete_record&type=delete_work_experience&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'bank_account'){
		var field_add = '&is_ajax=18&data=delete_record&type=delete_bank_account&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'contract'){
		var field_add = '&is_ajax=21&data=delete_record&type=delete_contract&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'salary'){
		var field_add = '&is_ajax=31&data=delete_record&type=delete_salary&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'leave'){
		var field_add = '&is_ajax=24&data=delete_record&type=delete_leave&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'annual_leave'){
		var field_add = '&is_ajax=24&data=delete_record&type=delete_annual_leave&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'shift'){
		var field_add = '&is_ajax=27&data=delete_record&type=delete_shift&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'location'){
		var field_add = '&is_ajax=30&data=delete_record&type=delete_location&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'imgdocument'){
		var field_add = '&is_ajax=30&data=delete_record&type=delete_imgdocument&';
		var tb_name = 'xin_table_'+tk_type;
	} else if(tk_type == 'flight'){
		var field_add = '&is_ajax=30&data=delete_record&type=delete_flight&';
		var tb_name = 'xin_table_'+tk_type;
	}
	
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			url: e.target.action,
			type: "post",
			data: '?'+obj.serialize()+field_add+"form="+action,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
				} else {
					$('.delete-modal').modal('toggle');
					$('#'+tb_name).dataTable().api().ajax.reload(function(){ 
						toastr.success(JSON.result);
					}, true);
					
				}
			}
		});
	});   
   /// delete a record
	$( document ).on( "click", ".delete", function() {
		$('input[name=_token]').val($(this).data('record-id'));
		$('input[name=token_type]').val($(this).data('token_type'));
		$('#delete_record').attr('action',site_url+'employees/delete_'+$(this).data('token_type')+'/'+$(this).data('record-id'));
	});
});	
$(document).ready(function(){
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
		
	$('.cont_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1990:' + (new Date().getFullYear() + 10),
	});	
	
});



$("#xin-form").submit(function(e){
	e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=salary&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
				$('.icon-spinner3').hide();
			} else {
			    $('#xin_table_salary').dataTable({
                    "bDestroy": true,
            		"ajax": {
                        url : site_url+"employees/salary/"+$('#user_id').val(),
                        type : 'GET'
                    },
            		"fnDrawCallback": function(settings){
            		$('[data-toggle="tooltip"]').tooltip();          
            		}
                });
				toastr.success(JSON.result);
				$('.save').prop('disabled', false);
			}
		}
	});
});



$(document).on("keyup", function () {
	var sum_total = 0;
	var deduction = 0;
	var allowance = 0;
	var net_salary = 0;
	$(".salary").each(function () {
		sum_total += +$(this).val();
	});
	
	$(".deduction").each(function () {
		deduction += +$(this).val();
	});
	
	$(".allowance").each(function () {
		allowance += +$(this).val();
	});
	
	$("#total").val(sum_total);
	$("#total_deduction").val(deduction);
	$("#total_allowance").val(allowance);
	
	
	var net_salary = sum_total - deduction;
	$("#net_salary").val(net_salary);
});