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
    $("[data-toggle='toggle']").bootstrapToggle();
    $('input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
});
$('document').ready(function(){
    $('.select2').select2();
});