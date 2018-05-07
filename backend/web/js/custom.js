$(document).ajaxStart(function(){
    moet.showLoader();
});

$(document).ajaxComplete(function(){
    moet.hideLoader();
});

$('form').on('beforeSubmit',function(){
    moet.showLoader();
});