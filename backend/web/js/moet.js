var moet = {
	'ajax': function(url, data, type) {
		var defer = jQuery.Deferred();
		data = data || {};
		type = type || 'get';

		$.ajax({
			url: url,
			type: type,
			data: data,
			dataType: 'json',
			beforeSend: function() {},
			complete: function() {},
			success: function(result) {
				defer.resolve(result);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				var result = {};
				result.message = 'Something went wrong';
				result.code = 500;
				result.data = {};
				defer.reject(result);
			}
		});

		return defer.promise();
	},
	'showLoader': function(){
		$("body").addClass('loader-enable');
	},
	'hideLoader': function (){
	    $("body").removeClass('loader-enable');
	},
	'showAlert': function (container, message, type){
	    container = container || '';
	    type = type || '';
	    message = message || '';

	    $element = $("#"+container);
	    if($element.length && message != ''){
	        switch(type){
	            case 'success':
	                    var className = 'success';                    
	                break;

	            case 'error':
	                    var className = 'danger';
	                break;

	            case 'warning':
	                    var className = 'warning';
	                break;

	            case 'info':
	                    var className = 'info';
	                break;

	            default:
	                    var className = 'danger';
	                break;
	        }

	        var uniqId = 'alert_msg_' + Math.round(new Date().getTime() + (Math.random() * 100));

	        var html = '<div id="'+uniqId+'" class="alert-'+className+' alert fade in cart-msg">';
	        html +=         '<button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>';
	        html +=         message;
	        html +=     '</div>';

	        $element.html($(html));

	        var cutoff = $(window).scrollTop();
	        if($("#" + uniqId).offset().top + $("#" + uniqId).height() < cutoff){
	            $('html,body').animate({scrollTop: $("#" + uniqId).offset().top - 10 } , 500);
	        }

	        setTimeout(function () {
	            $("#"+uniqId).fadeTo(2000, 500).slideUp(500, function () {
	                $("#"+uniqId).remove();
	            });
	        }, 2000);
	    }

	    return true;
	},
	'parseApi': function (apiData){
		var response = {
			success : apiData.status.success,
			message : apiData.status.message, 
			data : apiData.data, 
		};
	    return response;
	},
}