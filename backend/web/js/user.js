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
		}
	};

	$('#login-form').on('beforeSubmit', function (e) {
		var username = $('#loginform-username').val();
		var password = $('#loginform-password').val();
        userWebServices.login(username,password).then(function(result){
        	if (typeof result.data.authKey  !== "undefined"){
        		$.cookie("auth_key", result.data.authKey, { expires : 7300 });
        	}
        },function(result){
        	alert("Something went wrong.");
        });
        return false;
    });
})(jQuery);
