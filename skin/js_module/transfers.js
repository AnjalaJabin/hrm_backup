$(document).ready(function() {
var xin_table = $('#xin_table').dataTable({
	"bDestroy": true,
	"ajax": {
		url : base_url+"/transfer_list/",
		type : 'GET'
	},
	"fnDrawCallback": function(settings){
	$('[data-toggle="tooltip"]').tooltip();          
	}
});

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 

$('#description').summernote({
  height: 92,
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
	var transfer_id = button.data('transfer_id');
	var modal = $(this);
$.ajax({
	url : base_url+"/read/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=modal&data=transfer&transfer_id='+transfer_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal").html(response);
		}
	}
	});
});
$('.view-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var transfer_id = button.data('transfer_id');
	var modal = $(this);
$.ajax({
	url : base_url+"/read/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=modal&data=view_transfer&transfer_id='+transfer_id,
	success: function (response) {
		if(response) {
			$("#ajax_modal_view").html(response);
		}
	}
	});
});

// get locations
 jQuery("#company_id").change(function(){
	jQuery.get(base_url+"/location/"+jQuery(this).val(), function(data, status){
		jQuery('#location_ajax').html(data);
	});
});

// get departments
 jQuery("#location_id").change(function(){
	jQuery.get(base_url+"/department/"+jQuery(this).val(), function(data, status){
		jQuery('#department_ajax').html(data);
	});
});

/* Add data */ /*Form Submit*/
$("#xin-form").submit(function(e){
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	$('.icon-spinner3').show();
	var description = $("#description").code();
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=transfer&form="+action+"&description="+description,
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
});
$( document ).on( "click", ".delete", function() {
$('input[name=_token]').val($(this).data('record-id'));
$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
});