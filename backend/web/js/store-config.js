var tab3Status = '';
var step2Confirm = '1';
var step1Confirm = '1';

$('#tab2').click(function(event) 
{   
    if ($(this).attr('disabled')) {
        return false;
    }
    else
    {        
        if(step2Confirm == '1')
        {
            numOfSelves =$("#ex6SliderVal").val();
            if(numOfSelves != 0)
            {           
                for(i=0;i<numOfSelves;i++){
                   $("#canvas-container-"+i).empty();
                }            
            }
            $(".brand-drop").hide();
            $("#brandImage").attr("src",'');
            $("#tab3").attr('disabled', 'true');
            tab3Status = '';
            $.pjax.reload({container: '#employee',async:false});
        }
    }
});

$('#tab1').click(function(event) 
{   
    if ($(this).attr('disabled')) {
        return false;
    }
    else
    {        
        if(step1Confirm == '1')
        {
            numOfSelves =$("#ex6SliderVal").val();
            if(numOfSelves != 0){
                for(i=0;i<numOfSelves;i++){
                   $("#canvas-container-"+i).empty();
                }
            }
            $(".brand-drop").hide();
            $("#brandImage").attr("src",'');
            $("#tab2").attr('disabled','true');
            $("#tab3").attr('disabled', 'true');
            tab3Status = '';
        }
    }    
});

$('#tab3').click(function(event) {
    if ($(this).attr('disabled')) {
        return false;
    }else{
         $(".brand-drop").show();
    }
});

function hideShowDiv(data) 
{
    if (data === 'tab1') 
    {
        if(tab3Status == '1')
        {
            if(!confirm('Are you sure you want to change your Display ? Your current configuration will be reset.')) 
            {
                step1Confirm = '';
            }
            else
            {
                step1Confirm = '1';
            }
        }
        
        if(step1Confirm == '1')
        {
            addSuccessClass(data);
            $("#tab-step-2").hide();
            $("#tab-step-1").show();
            $("#tab-step-3").hide();
            $('.shelf').hide();        
        }
    }
    else if (data === 'tab2') 
    {
        if ($("#tab2").attr('disabled')) {
            return false;
        }
        else 
        {
            if(tab3Status == '1')
            {
                if(!confirm('Are you sure you want to change Define Products ? Your current configuration will be reset.')) 
                {
                    step2Confirm = '';
                }
                else
                {
                    step2Confirm = '1';
                }
            }
            
            if(step2Confirm == '1')
            {
                addSuccessClass(data);
                $("#tab-step-2").show();
                $("#tab-step-1").hide();
                $("#tab-step-3").hide();
                $.pjax.reload({container: '#employee',async:false});
                step1Confirm = 1;
            }
        }
        $('.shelf').hide();        
    }
    else if (data === 'tab3') 
    {
        if ($("#tab3").attr('disabled')) {
            return false;
        }
        else 
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
     $("#"+data).addClass("btn-success");
}

$(function() {
    $(".brand-drop").hide();
    getRack();
    $("#tab-step-2").hide();
    $("#tab-step-3").hide();
    $('#tab2').attr('disabled', 'disabled');
    $('#tab3').attr('disabled', 'disabled');

    $("#vtab1 .slider").slider(); //initialize slider
    $("#vtab1 .slider").css("width", "");

    $("#ex6").on("slide", function(slideEvt) {
        $("#ex6SliderVal").val(slideEvt.value);
        getRack();
    });

    $('#ex6SliderVal').keyup(function() {
        var v = $("#ex6SliderVal").val();
        $('#ex6').slider('setValue', v);
        getRack();
    });

    $("#hex6").on("slide", function(slideEvt) {
        $("#hex6SliderVal").val(slideEvt.value);
        getRack();
    });

    $('#hex6SliderVal').keyup(function() {
        var v = $("#hex6SliderVal").val();
        $('#hex6').slider('setValue', v);
        getRack();
    });

    $("#wex6").on("slide", function(slideEvt) {
        $("#wex6SliderVal").val(slideEvt.value);
        getRack();
    });

    $('#wex6SliderVal').keyup(function() {
        var v = $("#wex6SliderVal").val();
        $('#wex6').slider('setValue', v);
        getRack();
    });

    $("#dex6").on("slide", function(slideEvt) {
        $("#dex6SliderVal").val(slideEvt.value);
        getRack();
    });

    $('#dex6SliderVal').keyup(function() {
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
        prepareRack += '<div class="shelf" style="' + shelfFrameHeight + ';display:none;"><span>SHELF '+(i+1)+'</span></div>';
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

$("#dispaly_name").on('keyup', function() {
    $("#getName").text($("#dispaly_name").val());
});

function changeBrand(data){
     
 var id =data.id;
 if( $(".display"+id).hasClass("displayBlock")){
     
      $(".display"+id).css('display','none');
      $(".display"+id).removeClass('displayBlock');
      $("#brandImage").attr("src",'');
      $("#brand").val('');
 }else{
     $(".display"+id).css('display','block');
     $(".display"+id).addClass('displayBlock');
     $('img.brand-selected').not(".display"+id).css('display','none');
     $('img.brand-selected').not(".display"+id).removeClass('displayBlock');
     $("#brandImage").attr("src",data.src);
     $("#brand").val(id);
 }
}

jQuery(document).ready(function()
{    
    $("#tabs").tabs({active: 2});
    // Basic Wizard
    $("#tab-step-1 .next").click(function(e) {
        $('#validationWizard').bootstrapWizard('next');
        e.preventDefault();
    });
    $("#tab-step-2 .next").click(function(e) {
        $('#validationWizard').bootstrapWizard('next');
        e.preventDefault();
    });
    
    $("#tab-step-3 .next").click(function(e) 
    {
        var node = document.getElementById('frame-design');
  
        var options = { quality: 1  };
  
        domtoimage.toPng(node, options).then(function (dataUrl) 
        { 
            $.ajax({
                type: 'POST',
                url: uploadSelves,
                data :{'imageData':dataUrl},
                success: function(result)
                {
                    if(result.flag == 1){
                     $("#thumb_image").val(result.name);
                      $("#step_3").submit();return false;
                    }else{
                        alert("Please Try again later");
                    }
                }
            });
            return false;
        }).catch(function (error) {
            console.error('oops, something went wrong!', error);
        });
        return false;
    });    
    
    $("#tab-step-1 .reset-btn").click(function(e) 
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

    $("#tab-step-2 .reset-btn").click(function(e) 
    {
        $('#employee').iCheck('uncheck');
        return false;
    });
    
    $("#tab-step-3 .reset-btn").click(function(e) {
        alert("0");
        return false;
       
    });
    
   
    // With Form Validation Wizard
    var $validator = jQuery("#firstForm").validate({
        highlight: function(element) {
            jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            jQuery(element).closest('.form-group').removeClass('has-error');
        }
    });

    jQuery('#validationWizard').bootstrapWizard({
        //tabClass: 'nav nav-pills nav-justified nav-disabled-click',
        tabClass: 'nav-justified nav-disabled-click',
        onTabClick: function(tab, navigation, index) {
            return true;
        },
        onNext: function(tab, navigation, index) {
            
         
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
                        success: function(data)
                        {
                            addSuccessClass('tab2');
                            
                            $("#tab-step-1").hide();
                            $("#tab-step-2").show();
                         
                            var productObject = {};
                            $.pjax.reload({container: '#employee',async:false});
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
                        success: function(data) 
                        {
                            addSuccessClass('tab3');
                            
                            $("#tab-step-2").hide();
                            $("#tab-step-3").show();
                            tab3Status = '1';
                            $('.shelf').show();
                            $(".brand-drop").show();
                            $.pjax.reload({container:"#productsBrand",async:false});
                            $.pjax.reload({container:"#productsData" ,async:false});
                            $('#tab3').removeAttr('disabled');
                            $("#tab2").removeAttr('disabled');
                            $("#third").val(1);
                        }
                    });
                }
            }
        }
    });
});



$(document).on('ready pjax:success', function() 
{
    //Added by Hardik on 03-07-2018
    $(".list-btn").click(function(e) {
        e.preventDefault();
        $(this).children('img').attr("src", "../../images/list-btn.png");
        $(this).siblings('.grid-btn').children('img').attr("src", "../../images/grid-gray-btn.png");
        var list_id = $(this).attr("href");
        $(list_id).show();
        $(list_id).siblings(".grid-itmes").hide();
    });
    $(".grid-btn").click(function(e) {
        e.preventDefault();
        $(this).children('img').attr("src", "../../images/grid-btn.png");
        $(this).siblings('.list-btn').children('img').attr("src", "../../images/list-gray-btn.png");
        var grid_id = $(this).attr("href");
        $(grid_id).show();
        $(grid_id).siblings(".list-items").hide();
    });
    $(".grid-itmes li").click(function(e) {
        e.preventDefault();
        $(".grid-itmes li").removeClass('active');
        $(this).addClass('active');
    });
    $(".product-list .btn-box-tool").click(function() 
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
    //
});