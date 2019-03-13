$("#searchclear").click(function(){
    $(this).prev().val(''); 
    $('#search-users').submit();
    $('#search-stores').submit();
    $('#search-categories').submit();
    $('#search-catalogue').submit();
});

$(document).ajaxStart(function(){
    moet.showLoader();
});

$(document).ajaxComplete(function(){
    moet.hideLoader();
});

$('form').on('beforeSubmit',function(){
    moet.showLoader();
});
$(function () {
    $('input[type="checkbox"]:not(.not-icheck)').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
});
$(document).on("ready pjax:end", function() {
    $("[data-toggle='toggle']").bootstrapToggle();
    $('input[type="checkbox"]:not(.not-icheck)').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
});
$('document').ready(function(){
    $('.select2').select2();
    
    $(".numericOnly").keypress(function (e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
    });
    $(".numericOnly").keyup(function (e) {
        if($(this).val().length == 1)return false;
        $(this).val($(this).val().indexOf('0') == 0 ? $(this).val().substring(1) : $(this).val());
    });
    $('[name="shares[]"]').keyup(function(e){
        var $totalShare = 0;
        $.each( $('[name="shares[]"]'), function( key, value ) {
            if($(value).val() != '')
                $totalShare = $totalShare + parseInt($(value).val());
        });
        if($totalShare != 100) {
			$(this).parent().parent().css("background-color", "#d24737");
			$(this).parent().parent().css("color", "white");
			
			$('#messageBox').html("<div class='alert alert-danger'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>Total share should equal to 100</div>");
		    
			$(".auto_fill").attr('disabled','disabled');
			$('#totalShares').val($totalShare);
			if(parseInt($(this).val()) > 0){
				$(this).parent().parent().find('.manage-varietal').removeAttr('disabled');
				$(this).parent().parent().find('.manage-varietal').unbind('click', false);
			}else{
				$(this).parent().parent().find('.manage-varietal').attr('disabled','disabled');
				$(this).parent().parent().find('.manage-varietal').bind('click', false);
			}
            return false;
        }else{
			$.each( $('[name="shares[]"]'), function( key, value ) {
				
				$(this).parent().parent().css("background-color", "white");
				$(this).parent().parent().css("color", "black");
			});
			$('#messageBox').html("");
			$(".auto_fill").removeAttr('disabled');
		}
        if($totalShare == 100) {
            $(".auto_fill").removeAttr('disabled');
        }else{
            $(".auto_fill").attr('disabled','disabled');
        }
        if(parseInt($(this).val()) > 0){
            $(this).parent().parent().find('.manage-varietal').removeAttr('disabled');
			$(this).parent().parent().find('.manage-varietal').unbind('click', false);
        }else{
            $(this).parent().parent().find('.manage-varietal').attr('disabled','disabled');
			$(this).parent().parent().find('.manage-varietal').bind('click', false);
        }
        $('#totalShares').val($totalShare);
    });
    $('[name="varietalShares[]"]').keyup(function(e){
        var $totalShare = 0;
        $.each( $('[name="varietalShares[]"]'), function( key, value ) {
            if($(value).val() != '')
                $totalShare = $totalShare + parseInt($(value).val());
        });
		if($totalShare > 100) {
            alert('Share should equal to 100');
            //$(this).val(0);
            return false;
        }
        $('#totalVarietalShares').val($totalShare);
    });
});