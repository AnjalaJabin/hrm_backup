$(document).ready(function() {
   var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
		"ajax": {
            url : base_url+"/role_list/",
            type : 'GET'
        },
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
		

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
		var role_id = button.data('role_id');
		var modal = $(this);
	$.ajax({
		url : base_url+"/read/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=role&role_id='+role_id,
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
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=role&form="+action,
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
jQuery("#treeview_r1").kendoTreeView({
checkboxes: {
checkChildren: true,
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
},
check: onCheck,
dataSource: [

{ id: "", class: "role-checkbox custom-control-input", text: "Organization", add_info: "", value: "1", items: [
// sub 1
{ id: "" , class: "role-checkbox custom-control-input", text: "Company",  add_info: "Add/Edit/Delete", value: "3",},
{ id: "" , class: "role-checkbox custom-control-input", text: "Location",  add_info: "Add/Edit/Delete", value: "4",},
{ id: "" , class: "role-checkbox custom-control-input", text: "Department",  add_info: "Add/Edit/Delete", value: "5",},
{ id: "" , class: "role-checkbox custom-control-input", text: "Designation",  add_info: "Add/Edit/Delete", value: "6",},
{ id: "" , class: "role-checkbox custom-control-input", text: "Announcements",  add_info: "Add/Edit/Delete", value: "8",},
// { id: "" , class: "role-checkbox custom-control-input", text: "Policies",  add_info: "Add/Edit/Delete", value: "9",},
{ id: "" , class: "role-checkbox custom-control-input", text: "Expenses",  add_info: "Add/Edit/Delete", value: "10",},
]}, // sub 1 end
{ id: "" , class: "role-checkbox custom-control-input", text: "Employees",  add_info: "", value: "11",  items: [
{ id: "" , class: "role-checkbox custom-control-input", text: "Employees List",  add_info: "Add/Edit/View/Delete", value: "13",},
{ id: "" , class: "role-checkbox custom-control-input", text: "Set Roles",  add_info: "Add/Edit/Delete", value: "14",},
// { id: "", class: "role-checkbox custom-control-input", text: "Awards",  add_info: "Add/Edit/Delete", value: "15",},
// { id: "", class: "role-checkbox custom-control-input", text: "Transfers",  add_info: "Add/Edit/Delete", value: "16",},
// { id: "" , class: "role-checkbox custom-control-input", text: "Resignations",  add_info: "Add/Edit/Delete", value: "17",},
// { id: "" , class: "role-checkbox custom-control-input", text: "Travels",  add_info: "Add/Edit/Delete", value: "18",},

// { id: "", class: "role-checkbox custom-control-input", text: "Promotions",  add_info: "Add/Edit/Delete", value: "20",},
// { id: "", class: "role-checkbox custom-control-input", text: "Complaints",  add_info: "Add/Edit/Delete", value: "21",},
// { id: "", class: "role-checkbox custom-control-input", text: "Warnings",  add_info: "Add/Edit/Delete", value: "22",},
// { id: "", class: "role-checkbox custom-control-input", text: "Terminations",  add_info: "Add/Edit/Delete", value: "23",},
{ id: "", class: "role-checkbox custom-control-input", text: "Employees Last Login",  add_info: "View", value: "26",},
		{ id: "", class: "role-checkbox custom-control-input", text: "Leaves",  add_info: "Add/Edit/View/Delete", value: "32",},

// { id: "", class: "role-checkbox custom-control-input", text: "Employees Exit",  add_info: "Add/Edit/Delete", value: "27",}
]},

{ id: "", class: "role-checkbox custom-control-input", text: "Payroll",  add_info: "", value: "36",  items: [
// { id: "", class: "role-checkbox custom-control-input", text: "Payroll Templates",  add_info: "Create/Edit/Delete", value: "38",},
// { id: "", class: "role-checkbox custom-control-input", text: "Hourly Wages",  add_info: "Add/Edit/Delete", value: "39",},
{ id: "", class: "role-checkbox custom-control-input", text: "Manage Salary",  add_info: "Update/View", value: "40",},
{ id: "", class: "role-checkbox custom-control-input", text: "Generate Payslip",  add_info: "Generate/View", value: "41",},
{ id: "", class: "role-checkbox custom-control-input", text: "Payment History",  add_info: "View Payslip", value: "42",},
]},
]
});

jQuery("#treeview_r2").kendoTreeView({
checkboxes: {
checkChildren: true,
template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'  /><span class='custom-control-indicator'></span><span class='custom-control-description'>#= item.text #</span><span class='custom-control-info'>#= item.add_info #</span></label>"
},
check: onCheck,
dataSource: [
// { id: "", class: "role-checkbox custom-control-input", text: "Training",  add_info: "", value: "48",  items: [
// { id: "", class: "role-checkbox custom-control-input", text: "Training List",  add_info: "Add/Edit/View/Delete", value: "49"},
// { id: "", class: "role-checkbox custom-control-input", text: "Training Type",  add_info: "Add/Edit/Delete", value: "50",},
// { id: "", class: "role-checkbox custom-control-input", text: "Trainers List",  add_info: "Add/Edit/Delete", value: "51",},
// ]},
// { id: "", class: "role-checkbox custom-control-input", text: "Employees Directory",  add_info: "View", value: "52",},
{ id: "", class: "role-checkbox custom-control-input", text: "Settings",  add_info: "View/Update", value: "53",},
{ id: "", class: "role-checkbox custom-control-input", text: "Constants",  add_info: "Add/Edit/Delete", value: "54",},
{ id: "", class: "role-checkbox custom-control-input", text: "Company Documents",  add_info: "Add/Edit/Delete", value: "57",},
{ id: "", class: "role-checkbox custom-control-input", text: "Bank",  add_info: "Add/Edit/Delete", value: "58",},
{ id: "", class: "role-checkbox custom-control-input", text: "Account & Payments",  add_info: "Add/Edit/Delete", value: "59",},
{ id: "", class: "role-checkbox custom-control-input", text: "Contacts",  add_info: "Add/Edit/Delete", value: "60",},
{ id: "", class: "role-checkbox custom-control-input", text: "Approvals",  add_info: "View/Update", value: "100",},
{ id: "", class: "role-checkbox custom-control-input", text: "Flight Tickets",  add_info: "Add/Edit/Delete", value: "101",},
{ id: "", class: "role-checkbox custom-control-input", text: "Annual Leave",  add_info: "Add/Edit/Delete", value: "102",},
{ id: "", class: "role-checkbox custom-control-input", text: "Leave Salary",  add_info: "Add/Edit/Delete", value: "103",},
{ id: "", class: "role-checkbox custom-control-input", text: "Liability Report",  add_info: "Add/Edit/Delete", value: "110",},
{ id: "", class: "role-checkbox custom-control-input", text: "Gratuity",  add_info: "Add/Edit/Delete", value: "104",},
{ id: "", class: "role-checkbox custom-control-input", text: "Compensatory Leaves",  add_info: "Add/Edit/Delete", value: "105",},
{ id: "", class: "role-checkbox custom-control-input", text: "Employee Assets",  add_info: "Add/Edit/Delete", value: "106",},
{ id: "", class: "role-checkbox custom-control-input", text: "Employee Requests",  add_info: "Add/Edit/Delete", value: "107",},
{ id: "", class: "role-checkbox custom-control-input", text: "End Of Service",  add_info: "Add/Edit/Delete", value: "108",},
//
]
});
		
// show checked node IDs on datasource change
function onCheck() {
var checkedNodes = [],
		treeView = jQuery("#treeview2").data("kendoTreeView"),
		message;
		jQuery("#result").html(message);
}
$(document).ready(function(){
	$("#role_access").change(function(){
		var sel_val = $(this).val();
		if(sel_val=='1') {
			$('.role-checkbox').prop('checked', true);
		} else {
			$('.role-checkbox').attr("checked", false);
		}
	});
});