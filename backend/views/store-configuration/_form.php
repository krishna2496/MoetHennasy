<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;
$formUrl = Url::to(['store-configuration']);

?>

<div class="market-segments-form">
    <div class="row">
          <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
            
              <h4 class="panel-title">Configuration</h4>
            </div>
            <div class="panel-body panel-body-nopadding">
              
              <!-- BASIC WIZARD -->
              <div id="validationWizard" class="basic-wizard">
                
                <ul class="nav nav-pills nav-justified nav-disabled-click">
                  <li class="active"><a href="#vtab1" data-toggle="tab">CREATE YOUR DISPLAY</a></li>
                  <li><a href="#vtab2" data-toggle="tab">DIFINE PRODUCTS</a></li>
                  <li><a href="#vtab3" data-toggle="tab">CONFIGURATION</a></li>
                </ul>
            <?= Html::beginForm($formUrl, 'post', ['data-pjax' => '', 'id' => 'firstForm']); ?>
              
                <div class="tab-content">
                  
                  <div class="tab-pane active" id="vtab1">
                      
                   <input type="text" value="" class="slider form-control" data-slider-min="-200" data-slider-max="200"
                         data-slider-step="5" data-slider-value="[-100,100]" data-slider-orientation="horizontal"
                         data-slider-selection="before" data-slider-tooltip="show" data-slider-id="red">

                  <p>data-slider-id="red"</p>
                      
                      <div class="form-group">
                        <label class="col-sm-4 control-label">Firstname</label>
                        <div class="col-sm-8">
                              <?= Html::input('firstname', 'firstname', '', ['class' => 'form-control','id' => 'firstname','required'=>'']) ?>
                        
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-sm-4 control-label">Lastname</label>
                        <div class="col-sm-8">
                             <?= Html::input('lastname', 'lastname', '', ['class' => 'form-control','id' => 'lastname','required'=>'']) ?>
                         
                        </div>
                      </div>
                      
                      
                      
                  </div>
                  <div class="tab-pane" id="vtab2">
                    
                  </div>
                  
                  <div class="tab-pane" id="vtab3">
                      
                      
                  </div>
                  
                  
                </div><!-- tab-content -->
               <?= Html::endForm(); ?>
                
                <ul class="pager wizard">
                    <li class="previous disabled"><a href="javascript:void(0)">Previous</a></li>
                    <li class="next"><a href="javascript:void(0)">Next</a></li>
                  </ul>
                
              </div><!-- #validationWizard -->
              
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function(){

  
    $(function () {
    /* BOOTSTRAP SLIDER */
    $('.slider').slider()
  })
  // Basic Wizard
  jQuery('#basicWizard').bootstrapWizard();
  
  // Progress Wizard
  $('#progressWizard').bootstrapWizard({
    'nextSelector': '.next',
    'previousSelector': '.previous',
    onNext: function(tab, navigation, index) {
      var $total = navigation.find('li').length;
      var $current = index+1;
      var $percent = ($current/$total) * 100;
      jQuery('#progressWizard').find('.progress-bar').css('width', $percent+'%');
    },
    onPrevious: function(tab, navigation, index) {
      var $total = navigation.find('li').length;
      var $current = index+1;
      var $percent = ($current/$total) * 100;
      jQuery('#progressWizard').find('.progress-bar').css('width', $percent+'%');
    },
    onTabShow: function(tab, navigation, index) {
      var $total = navigation.find('li').length;
      var $current = index+1;
      var $percent = ($current/$total) * 100;
      jQuery('#progressWizard').find('.progress-bar').css('width', $percent+'%');
    }
  });
  
  // Disabled Tab Click Wizard
  jQuery('#disabledTabWizard').bootstrapWizard({
    tabClass: 'nav nav-pills nav-justified nav-disabled-click',
    onTabClick: function(tab, navigation, index) {
      return false;
    }
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
    tabClass: 'nav nav-pills nav-justified nav-disabled-click',
    onTabClick: function(tab, navigation, index) {
      return false;
    },
    onNext: function(tab, navigation, index) {
      var $valid = jQuery('#firstForm').valid();
      if(!$valid) {
        
        $validator.focusInvalid();
        return false;
      }
    }
  });	  

 $(function () {
    /* BOOTSTRAP SLIDER */
    $('.slider').slider()
});
  
});
 
</script>