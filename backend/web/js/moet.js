var moet = {
	'ajax': function(data, url, type) {
		$.ajax({
			url: url,
			type: type,
			data: data,
			dataType: 'json',
			beforeSend: function() {
				console.log('loading');
			},
			complete: function() {
				console.log('completed');
			},
			success: function(result) {
				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
}