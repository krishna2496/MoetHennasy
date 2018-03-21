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
				if(result.code == '200'){
					defer.resolve(result);
				}else{
					defer.reject(result);
				}
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
	}
}