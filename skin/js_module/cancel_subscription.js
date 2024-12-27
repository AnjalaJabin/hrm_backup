$(document).ready(function() {
	
	/* Edit data */ /*Form Submit*/
	$("#xin-form").submit(function(e){ 
	e.preventDefault();
	    var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: base_url+'/cancel_subscription_update/',
			data: obj.serialize()+"&is_ajax=1&edit_type=cancel_subscription&form="+action,
			cache: false,
			success: function (JSON) { 
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} 
				else if (JSON.redirect != '') {
				    window.location.replace(JSON.redirect);
				}else {
					toastr.success(JSON.result);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				}
			}
		});
	});
	
});