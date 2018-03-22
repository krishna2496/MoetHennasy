(function($) {
	var userWebServices = {
		list: function(){

		},
		login: function(username, password){
			var defer = jQuery.Deferred();
			var loginUrl = appUrl+'site/login';
			var data = {username:username,password:password};
			moet.ajax(loginUrl,data,'post').then(function(result){
				defer.resolve(result);
			},function(result){
				defer.reject(result);
			});
			return defer.promise();
		},
		forgotPassword:function(email){
			var defer = jQuery.Deferred();
			var requestUrl = appUrl+'site/request-password-reset';
			var data = {email:email};
			moet.ajax(requestUrl,data,'post').then(function(result){
				defer.resolve(result);
			},function(result){
				defer.reject(result);
			});
			return defer.promise();
		},
		resetPassword:function(password,token){
			var defer = jQuery.Deferred();
			var resetUrl = appUrl+'site/reset-password';
			var data = {password:password,token:token};
			moet.ajax(resetUrl,data,'post').then(function(result){
				defer.resolve(result);
			},function(result){
				defer.reject(result);
			});
			return defer.promise();
		}
	};

	$('#login-form').on('beforeSubmit', function (e) {
		var username = $('#loginform-username').val();
		var password = $('#loginform-password').val();
        userWebServices.login(username,password).then(function(result){
        	result = moet.parseApi(result);
        	if(result.success == 1){
        		if (typeof result.data.user.auth_key  !== "undefined"){
	        		$.cookie("auth_key", result.data.user.auth_key, { expires : 7300, path : '/' });
	        	}
	        	window.location.href = adminUrl;
        	} else {
        		moet.showAlert('flash-message-block',result.message,'error');
        	}
        });
        return false;
    });

	$('#request-password-reset-form').on('beforeSubmit', function (e) {
		var email = $('#passwordresetrequestform-email').val();
        userWebServices.forgotPassword(email).then(function(result){
        	result = moet.parseApi(result);
        	if(result.success == 1){
        		moet.showAlert('flash-message-block',result.message,'success');
        	} else {
        		moet.showAlert('flash-message-block',result.message,'error');
        	}
        });
        return false;
    });

	$('#reset-password-form').on('beforeSubmit', function (e) {
		var password = $('#resetpasswordform-password').val();
		var token = $('#resetpasswordform-token').val();
        userWebServices.resetPassword(password,token).then(function(result){
        	result = moet.parseApi(result);
        	if(result.success == 1){
        		moet.showAlert('flash-message-block',result.message,'success');
        	} else {
        		moet.showAlert('flash-message-block',result.message,'error');
        	}
        });
        return false;
    });
})(jQuery);
