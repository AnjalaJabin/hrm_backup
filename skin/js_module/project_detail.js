$(document).ready(function() {

/* Edit task data */
$("#update_status").submit(function(e){
/*Form Submit*/
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=3&type=update_status&update=1&view=task&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.save').prop('disabled', false);				
			}
		}
	});
});

/* update task employees */
$("#assign_project").submit(function(e){
	
/*Form Submit*/
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=4&type=project_user&view=user&form="+action,
		cache: false,
		success: function (JSON) {
			jQuery.get(site_url+"project/project_users/"+jQuery('#project_id').val(), function(data, status){
				jQuery('#all_employees_list').html(data);
			});
			$('.save').prop('disabled', false);
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('.save').prop('disabled', false);
			} else {
				toastr.success(JSON.result);
				$('.save').prop('disabled', false);				
			}
		}
	});
});

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
});