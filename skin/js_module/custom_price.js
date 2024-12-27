$(document).ready(function() {
    
	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("add_type", 'custom_price');
		fd.append("form", action);
		e.preventDefault();
		$('.icon-spinner3').show();
		$('.save').prop('disabled', true);
		
		$.ajax({
			url: site_url+'/package/add_custom_price/',//e.target.action,
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
					$('#xin-form')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
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
});