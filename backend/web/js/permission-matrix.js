
var totalPermisssions= totalPermissionCount;
$.each(roleLabels, function( i, val ) {
	if($('.role'+val.id+':checked').length == totalPermisssions)
	{
		$("#role"+val.id).prop('checked', true);
	}
});

$('.selectAll').on('ifChecked', function (event) {
    var roleId = $(this).attr('id');
    $('.'+roleId).iCheck('check');
});

$('.selectAll').on('ifUnchecked', function (event) {
    var roleId = $(this).attr('id');
    $('.'+roleId).iCheck('uncheck');
});