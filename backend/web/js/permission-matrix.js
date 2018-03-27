//for check all permission boxes of particular role when role is checked
function checkboxClick(data)
{
	var roleId=data.value;

		$('#role'+roleId).change(function() {
        if ($(this).is(':checked'))
        {
        	
        	  $(".role"+roleId).prop('checked', true);
        }
        else
        {
        	$(".role"+roleId).prop('checked', false);
        }
    });
}

var totalPermisssions= totalPermissionCount;
$.each(roleLabels, function( i, val ) {
	if($('.role'+val.id+':checked').length == totalPermisssions)
	{
		$("#role"+val.id).prop('checked', true);
	}
});