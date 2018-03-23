(function($) {
	var userWebServices = {
		list: function(){

		},
		login: function(username, password, deviceType, deviceToken){
			deviceToken = deviceToken || '';
			var defer = jQuery.Deferred();
			var loginUrl = appUrl+'site/login';
			var data = {username:username,password:password,deviceType:deviceType,deviceToken:deviceToken};
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
})(jQuery);
