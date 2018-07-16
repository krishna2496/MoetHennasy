var tab3Status = '';
var step2Confirm = '1';
var step1Confirm = '1';


$('#tab2').click(function (event)
{
    if ($(this).attr('disabled')) {
        return false;
    } else
    {
        if (step2Confirm == '1')
        {
            numOfSelves = $("#ex6SliderVal").val();
            if (numOfSelves != 0)
            {
                for (i = 0; i < numOfSelves; i++) {
                    $("#canvas-container-" + i).empty();
                }
            }
            $(".brand-drop").hide();
            $('#brandImageHolder').html('Select Brand');
            $("#tab3").attr('disabled', 'true');
            tab3Status = '';
            $.pjax.reload({container: '#employee', async: false});
        }
    }
});

$('#tab1').click(function (event)
{
    if ($(this).attr('disabled')) {

        return false;
    } else
    {
        if (step1Confirm == '1')
        {
            numOfSelves = $("#ex6SliderVal").val();
            if (numOfSelves != 0) {
                for (i = 0; i < numOfSelves; i++) {
                    $("#canvas-container-" + i).empty();
                }
            }
            $(".brand-drop").hide();
            $('#brandImageHolder').html('Select Brand');
            $("#tab2").attr('disabled', 'true');
            $("#tab3").attr('disabled', 'true');
            tab3Status = '';
        }
    }
});

$('#tab3').click(function (event) {
    if ($(this).attr('disabled')) {
        return false;
    } else {
        $(".brand-drop").show();
    }
});

function hideShowDiv(data)
{
    if (data === 'tab1')
    {
        if (tab3Status == '1')
        {
            if ($("#tab1").attr('disabled')) {
                return false;
            } else {
                if (!confirm('Are you sure you want to change your Display ? Your current configuration will be reset.'))
                {
                    step1Confirm = '';
                } else
                {
                    step1Confirm = '1';
                }
            }
        }

        if (step1Confirm == '1')
        {
            addSuccessClass(data);
            $("#tab-step-2").hide();
            $("#tab-step-1").show();
            $("#tab-step-3").hide();
            $('.shelf').hide();
        }
    } else if (data === 'tab2')
    {
        if ($("#tab2").attr('disabled')) {
            return false;
        } else
        {
            if (tab3Status == '1')
            {
                if (!confirm('Are you sure you want to change Define Products ? Your current configuration will be reset.'))
                {
                    step2Confirm = '';
                } else
                {
                    step2Confirm = '1';
                }
            }

            if (step2Confirm == '1')
            {
                addSuccessClass(data);
                $("#tab-step-2").show();
                $("#tab-step-1").hide();
                $("#tab-step-3").hide();
                $('.shelf').hide();
                $.pjax.reload({container: '#employee', async: false});
                step1Confirm = 1;
            }
        }
    } else if (data === 'tab3')
    {
        if ($("#tab3").attr('disabled')) {
            return false;
        } else
        {
            addSuccessClass(data);
            $("#tab-step-2").hide();
            $("#tab-step-1").hide();
            $("#tab-step-3").show();
            tab3Status = '1';
            $('.shelf').show();
        }
    }
}

function addSuccessClass(data)
{
    $(".stepwizard-step a").removeClass("btn-success");
    $("#" + data).addClass("btn-success");

    if (data == 'tab2')
    {
        $('#tab-step-2').removeClass('col-sm-5');
        $('#tab-step-2').addClass('col-sm-6');
        $('#frame-design').parent().removeClass('col-sm-7');
        $('#frame-design').parent().addClass('col-sm-6');
    } else
    {
        $('#tab-step-2').removeClass('col-sm-6');
        $('#tab-step-2').addClass('col-sm-5');
        $('#frame-design').parent().removeClass('col-sm-6');
        $('#frame-design').parent().addClass('col-sm-7');
    }
}

$(function () {
    $(".brand-drop").hide();
    getRack();
    $("#tab-step-2").hide();
    $("#tab-step-3").hide();
    $('#tab2').attr('disabled', 'disabled');
    $('#tab3').attr('disabled', 'disabled');

    $("#vtab1 .slider").slider(); //initialize slider
    $("#vtab1 .slider").css("width", "");

    $("#ex6").on("slide", function (slideEvt) {
        $("#ex6SliderVal").val(slideEvt.value);
        getRack();
    });

    $('#ex6SliderVal').keyup(function () {
        var v = $("#ex6SliderVal").val();
        $('#ex6').slider('setValue', v);
        getRack();
    });

    $("#hex6").on("slide", function (slideEvt) {
        $("#hex6SliderVal").val(slideEvt.value);
        getRack();
    });

    $('#hex6SliderVal').keyup(function () {
        var v = $("#hex6SliderVal").val();
        $('#hex6').slider('setValue', v);
        getRack();
    });

    $("#wex6").on("slide", function (slideEvt) {
        $("#wex6SliderVal").val(slideEvt.value);
        getRack();
    });

    $('#wex6SliderVal').keyup(function () {
        var v = $("#wex6SliderVal").val();
        $('#wex6').slider('setValue', v);
        getRack();
    });

    $("#dex6").on("slide", function (slideEvt) {
        $("#dex6SliderVal").val(slideEvt.value);
        getRack();
    });

    $('#dex6SliderVal').keyup(function () {
        var v = $("#dex6SliderVal").val();
        $('#edx6').slider('setValue', v);
        getRack();
    });
});

function getRack()
{
    var noOfShelves = parseInt($("#ex6SliderVal").val());
    var rackHeight = parseInt($("#hex6SliderVal").val());
    var rackWidth = parseInt($("#wex6SliderVal").val());
    var rackDepth = parseInt($("#dex6SliderVal").val());
    var rackRatio = getRatio(rackWidth);

    var newRackHeight = Math.round((rackRatio * rackHeight) / (noOfShelves));
    var newRackWidth = Math.round(rackRatio * rackWidth);
    var newRackDepth = Math.round((rackRatio * rackDepth) / (noOfShelves));

    var frameTotalWidth = (parseInt(newRackWidth) + 24 + 40);
    var frameTotalHeight = (((parseInt(newRackHeight) + 20) * noOfShelves) + 145);

    var canvasFrameWidth = 'width:' + (parseInt(newRackWidth) + 40) + 'px';
    var canvasFrameHeight = 'height:' + (parseInt(newRackHeight) + 20) + 'px';

    var shelfFrameHeight = 'height:' + (parseInt(newRackHeight) + 5) + 'px';

    var canvasInternalWidth = 'width:' + parseInt(newRackWidth) + 'px';
    var canvasInternalHeight = 'height:' + parseInt(newRackHeight) + 'px';

    $("#div-frame-content").width(frameTotalWidth);
    $("#div-frame-content").height(frameTotalHeight);
    rack = [];

    for (i = 0; i < noOfShelves; i++) {
        //rack.push('<div id="main-rack"><div id="canvas-container-' + i + '" style="width:' + parseInt(newRackWidth) + 'px; height:' + parseInt(newRackHeight) + 'px"><canvas id="real-canvas-' + i + '" style="width:' + parseInt(newRackWidth) + 'px; height:' + parseInt(newRackHeight) + 'px" width="' + parseInt(newRackWidth) + 'px" height="' + parseInt(newRackHeight) + ';"></canvas></div></div>');

        /*var prepareRack = '<div class="mid-sec-frames" style="' + canvasFrameWidth + ';' + canvasFrameHeight + ';">';
         prepareRack += '<div class="bottle-sec" id="canvas-container-' + i + '" style="' + canvasInternalWidth + ';' + canvasInternalHeight + ';">';*/

        var prepareRack = '<div class="mid-sec-frames" style="' + canvasFrameHeight + ';">';
        prepareRack += '<div class="shelf" style="' + shelfFrameHeight + ';display:none;"><span>SHELF ' + (i + 1) + '</span></div>';
        prepareRack += '<div class="bottle-sec" id="canvas-container-' + i + '" style="' + canvasInternalHeight + ';">';
        //prepareRack += '<canvas class="shelf-canvas" id="real-canvas-' + i + '" style="' + canvasInternalWidth + ';' + canvasInternalHeight + ';"></canvas>'; // Not in use so commented canvas         
        prepareRack += '</div>';
        prepareRack += '<div class="fms-bt"><span class="fms-lt"></span><span class="fms-rt"></span></div>';
        prepareRack += '</div>';
        rack.push(prepareRack);
    }

    $("#canvas-generator").html(rack);
    /*for (i = 0; i < noOfShelves; i++) {
     //$("#real-canvas-" + i + "").RackCanvas(i);
     }*/
}

function getRatio(width) {
    var widthValue = rackWidthValue;
    var ratio = (parseInt(widthValue) / parseInt(width));
    $("#ratio").val(ratio);
    return ratio;
}

$("#dispaly_name").on('keyup', function () {
    $("#getName").text($("#dispaly_name").val());
});


function changeBrand(data) {

    var id = data.id;
    if ($(".display" + id).hasClass("displayBlock")) {

        $(".display" + id).css('display', 'none');
        $(".display" + id).removeClass('displayBlock');
        $('#brandImageHolder').html('Select Brand');
        $("#brand").val('');
    } else {
        $(".display" + id).css('display', 'block');
        $(".display" + id).addClass('displayBlock');
        $('img.brand-selected').not(".display" + id).css('display', 'none');
        $('img.brand-selected').not(".display" + id).removeClass('displayBlock');
        $('#brandImageHolder').html('<img src="' + data.src + '" alt="Select Brand" id="brandImage">');
        $("#brand").val(id);
    }
}

jQuery(document).ready(function ()
{
    $("#tabs").tabs({active: 2});
    // Basic Wizard
    $("#tab-step-1 .next").click(function (e) {
        $('#validationWizard').bootstrapWizard('next');
        e.preventDefault();
    });
    $("#tab-step-2 .next").click(function (e) {
        $('#validationWizard').bootstrapWizard('next');
        e.preventDefault();
    });

    $("#tab-step-3 .next").click(function (e)
    {

        if (reviewFlag == 1) {
//            $('#modalReview').modal('show');
            var dataURL = reviewStoreUrl;  
            $('#modalReview').modal({ show: true,keyboard: true,backdrop: 'static'}).load(dataURL, function () {
                $("#rating").hide();
                $('.toggle').bootstrapToggle();


                $('#submitQuestion').on('click', function (e)
                {
                    var rating = 0;
                    var feedBack = 0;
                    e.stopImmediatePropagation();

                    if ($('#review').is(':visible')) {
                        var arrayData = [];
                        $("input:checkbox[name=answer]:checked").each(function () {
                            console.log(this);
                            arrayData.push($(this).attr('ans'));
                        });

                        var action = 'feedback';
                        feedBack = 1;
                        var data = {action: action, data: arrayData};
                    } else {
                        var star = $('span.filled-stars').width();
                        var starRating = parseInt(star) / 32;

                        var action = 'rating';
                        rating = 1;
                        var data = {action: action, data: starRating};
                    }
                    if(starRating == 0){
                        alert("Rating Can not be blank");
                        return false;
                    }

                    moet.ajax(questionUrl, data, 'post').then(function (result) {
                        $("#ratings-rating").rating({min: 0, max: maxStar, step: 1, stars: maxStar, size: 'xs'});

                        if (result == 1) {
                            if (feedBack == 1) {
                                //alert("Feeback Saved Succesfully");
                                $("#review").hide();
                                $("#rating").show();
                                $('.modal-header > .modal-title',$('#modalReview')).html("Review");
                                $('.modal-body > p:first',$('#modalReview')).html("<strong>Your configuration have been saved.</strong> Could you please rate your configuration:");
                                $('#submitQuestion').text('Save and Next');
                            } else {
                                alert("Rating Saved Succesfully");
                                $('#modalReview').modal('hide');
                                moet.showLoader();
                                var node = document.getElementById('frame-design');
                                var options = {scale:true};

                                domtoimage.toPng(node, options).then(function (dataUrl)
                                {
                                    moet.hideLoader();
                                    $.ajax({
                                        type: 'POST',
                                        url: uploadSelves,
                                        data: {'imageData': dataUrl},
                                        success: function (result)
                                        {
                                            if (result.flag == 1) {
                                                $("#thumb_image").val(result.name);
                                                $("#step_3").submit();
                                                return false;
                                            } else {
                                                alert("Please Try again later");
                                            }
                                        }
                                    });
                                    return false;
                                }).catch(function (error) {
                                    moet.hideLoader();
                                    console.error('oops, something went wrong!', error);
                                });
                                return false;
                            }
                        } else {
                            alert("Please Try again later");
                        }

                    }, function (result) {
                        alert('Fail');
                    });
                });


            });
            return false;
        }else{
            moet.showLoader();
            var node = document.getElementById('frame-design');
                                var options = {scale:true};

                                domtoimage.toPng(node, options).then(function (dataUrl)
                                {
                                     moet.hideLoader();
                                    $.ajax({
                                        type: 'POST',
                                        url: uploadSelves,
                                        data: {'imageData': dataUrl},
                                        success: function (result)
                                        {
                                            if (result.flag == 1) {
                                                $("#thumb_image").val(result.name);
                                                $("#step_3").submit();
                                                return false;
                                            } else {
                                                alert("Please Try again later");
                                            }
                                        }
                                    });
                                    return false;
                                }).catch(function (error) {
                                    moet.hideLoader();
                                    console.error('oops, something went wrong!', error);
                                });
                                return false;
        }

    });

    $("#tab-step-1 .reset-btn").click(function (e)
    {
        $('#dispaly_name').val('');
        $('#ex6SliderVal').val(2);
        $('#ex6').slider('setValue', 2);
        $('#hex6SliderVal').val(100);
        $('#hex6').slider('setValue', 100);
        $('#wex6SliderVal').val(100);
        $('#wex6').slider('setValue', 100);
        $('#dex6SliderVal').val(30);
        $('#dex6').slider('setValue', 30);
        $('.brand-list').iCheck('uncheck');
        getRack();
        return false;
    });

    $("#tab-step-2 .reset-btn").click(function (e)
    {
        $('#employee').iCheck('uncheck');
        return false;
    });

    $("#tab-step-3 .reset-btn").click(function (e) {
        return false;
    });

    if (isUpdate == 1) {

        $("#tab1").attr('disabled', 'true');
        $("#tab2").attr('disabled', 'true');
        $("#tab3").removeAttr('disabled');
        $("#tab1").parent('li').removeAttr('id');
        $("#tab3").parent('li').attr('id', 3);

        $('#tab3').click();
        addSuccessClass('tab3');

        $("#tab-step-2").hide();
        $("#tab-step-3").show();
        tab3Status = '1';
        $('.shelf').show();
        $(".brand-drop").show();
        $.pjax.reload({container: "#productsBrand", async: false});
        $.pjax.reload({container: "#productsData", async: false});
        $('#tab3').removeAttr('disabled');

        $("#third").val(1);
        $("#displayName").text($("#getName").text());
    }

    // With Form Validation Wizard
    var $validator = jQuery("#firstForm").validate({
        highlight: function (element) {
            jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
            jQuery(element).closest('.form-group').removeClass('has-error');
        }
    });

    jQuery('#validationWizard').bootstrapWizard({
        //tabClass: 'nav nav-pills nav-justified nav-disabled-click',
        tabClass: 'nav-justified nav-disabled-click',
        onTabClick: function (tab, navigation, index) {
            return true;
        },
        onNext: function (tab, navigation, index) {


            if (index == 1) {

                if ($("#firstForm input[name='brands[]']:checkbox:checked").length <= 0)
                {
                    alert("Please Select at least one brand");
                    return false;
                }
            }
            if (index == 2) {
                if (productArry == '')
                {
                    alert("Please Select at least one product");
                    return false;
                }
            }
            var $valid = jQuery('#firstForm').valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            } else
            {
                if (index == 1)
                {
                    url = rackFromURL;
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $("#firstForm").serialize(),
                        success: function (data)
                        {
                            addSuccessClass('tab2');

                            $("#tab-step-1").hide();
                            $("#tab-step-2").show();

                            var productObject = {};
                            $.pjax.reload({container: '#employee', async: false});
                            $("#tab2").removeAttr('disabled');
                        }
                    });
                }
                if (index == 2) {

                    url = rackProductFromURL;

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {productObject: productObject},
                        success: function (data)
                        {
                            addSuccessClass('tab3');

                            $("#tab-step-2").hide();
                            $("#tab-step-3").show();
                            tab3Status = '1';
                            $('.shelf').show();
                            $(".brand-drop").show();
                            $.pjax.reload({container: "#productsBrand", async: false});
                            $.pjax.reload({container: "#productsData", async: false});
                            $('#tab3').removeAttr('disabled');
                            $("#tab2").removeAttr('disabled');
                            $("#third").val(1);
                            $("#displayName").text($("#getName").text());
                        }
                    });
                }
            }
        }
    });
});



$(document).on('ready pjax:success', function ()
{

    //Added by Hardik on 03-07-2018
    $(".list-btn").click(function (e) {
        e.preventDefault();
        $(this).children('img').attr("src", baseUrl+"/images/list-btn.png");
        $(this).siblings('.grid-btn').children('img').attr("src", baseUrl+"/images/grid-gray-btn.png");
        var list_id = $(this).attr("href");
        $(list_id).show();
        $(list_id).siblings(".grid-itmes").hide();
    });
    $(".grid-btn").click(function (e) {
        e.preventDefault();
        $(this).children('img').attr("src", baseUrl+"/images/grid-btn.png");
        $(this).siblings('.list-btn').children('img').attr("src", baseUrl+"/images/list-gray-btn.png");
        var grid_id = $(this).attr("href");
        $(grid_id).show();
        $(grid_id).siblings(".list-items").hide();
    });
    $(".grid-itmes li").click(function (e) {
        e.preventDefault();
        $(".grid-itmes li").removeClass('active');
        $(this).addClass('active');
    });
    $(".product-list .btn-box-tool").click(function ()
    {
        if ($(this).children('i').hasClass("fa-plus"))
        {
            $(this).children('i').removeClass("fa-plus");
            $(this).children('i').addClass("fa-minus");
        } else {
            $(this).children('i').removeClass("fa-minus");
            $(this).children('i').addClass("fa-plus");
        }
    });
    if (brandThumbId != 0) {
        var id = brandThumbId;
        var imgSrc = $('.brand-images[id="' + id + '"]').attr('src');
        $(".display" + id).css('display', 'block');
        $(".display" + id).addClass('displayBlock');
        $('img.brand-selected').not(".display" + id).css('display', 'none');
        $('img.brand-selected').not(".display" + id).removeClass('displayBlock');
        $('#brandImageHolder').html('<img src="' + imgSrc + '" alt="Select Brand" id="brandImage">');
        $("#brand").val(id);
    }
    //
});