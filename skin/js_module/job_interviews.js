$(document).ready(function() {
    
var xin_table = $('#xin_table').dataTable({
	"bDestroy": true,
	"ajax": {
		url : base_url+"/interview_list/",
		type : 'GET'
	},
	"fnDrawCallback": function(settings){
	    console.log("xin table");
	$('[data-toggle="tooltip"]').tooltip();          
	}
});

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 

$('#description').summernote({
  height: 95,
  minHeight: null,
  maxHeight: null,
  focus: false
});
$('.note-children-container').hide();

// Clock
var input = $('.timepicker').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
});

// get job candidates
 jQuery("#job_id").change(function(){
	jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
		jQuery('#interviewees_ajax').html(data);
	});
});

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
			if (JSON.error !=='') {
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

/* Add data */ /*Form Submit*/
$("#xin-form").submit(function(e){
e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$('.save').prop('disabled', true);
	var description = $("#description").code();
	$('.icon-spinner3').show();
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=interview&form="+action+"&description="+description,
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
$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'))+'/';
});
