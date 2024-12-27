$(document).ready(function() {
  $('.btn_monthly').on('click', function(){
      $('.btn_monthly').addClass('btn-info');
      $('.btn_yearly').removeClass('btn-info');
      $('.price_yearly').hide();
      $('.price_monthly').show();
      $('.plan_book_type').val('monthly');
  });
  
  $('.btn_yearly').on('click', function(){
      $('.btn_yearly').addClass('btn-info');
      $('.btn_monthly').removeClass('btn-info');
      $('.price_yearly').show();
      $('.price_monthly').hide();
      $('.plan_book_type').val('yearly');
  });
  
  
  $('.price_up_btn').on('click',function(){
      var plan = $(this).data('plan');
      $('.plan__type').val(plan);
      $("#price_form").submit();
  })
  
  
  $("#price_form").submit(function(e){
	e.preventDefault();
	    var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		$.ajax({
			type: "POST",
			url: site_url+'/package/update/',
			data: obj.serialize()+"&is_ajax=1&edit_type=package&form="+action,
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