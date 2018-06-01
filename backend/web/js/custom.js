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
    $('input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
});
$(document).on("ready pjax:end", function() {
    $('input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
});
