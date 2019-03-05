<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$formUrl=Url::to('products');
?>
<?php Pjax::begin(['id' => 'pjaxCustomers','timeout' => false,'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
<div class="rule_admin" id="rule_admin">
    <div class="row"> 
        <div class="col-xs-12">
            <?php $form = ActiveForm::begin([ 'method' => 'GET','options' => ['data-pjax' => '']]); ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        
                        <div class="col-md-6">
                              <?= $form->field($model, 'market_id')->dropDownList($markets, ['prompt' => 'Select One','id' => 'market_id']); ?>  
                        </div> 
                        <div class="col-md-6">
                              <?= $form->field($model, 'market_cluster_id')->dropDownList($brands, ['prompt' => 'Select One','id' => 'market_segment_id']); ?>  
                        </div>
                        <div class="col-md-6">
                               <?= $form->field($model, 'brand_id')->dropDownList($brands, ['multiple'=>'multiple','class'=>'form-control select2']); ?>  
                        </div>
                        <div class="col-md-6">
                               <?= $form->field($model, 'product_category_id')->dropDownList($productCategory, ['prompt' => 'Select One']); ?>  
                        </div>
                        <div class="row" align="center">
                            <div class="col-xs-6">
                                <?= Html::submitButton('OK', ['class' => 'btn btn-primary pull-left mw-md']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php

$this->registerJs(
   '$("document").ready(function(){ 
		$("#pjaxCustomers").on("pjax:end", function() {
                alert(0);
			$.pjax.reload({container:"#countries"});  //Reload GridView
		});
    });'
);
?>
<?php yii\widgets\Pjax::end() ?>
<script type="text/javascript">
    $( document ).ready(function() {
        $('.select2').select2({
            placeholder: "Select Brand" 
        });
    });
    function getMarketSegments(data){
        var str = "<option value>Select Cluster</option>";
        moet.ajax("<?php echo CommonHelper::getPath('admin_url')?>stores/ajax-get-segment",data,'post').then(function(result){
            if(result.status.success == 1) {
                if(result.data.segments.length > 0) {
                    $.each(result.data.segments, function(key, value){
                       
                        str += "<option value="+value.marketSegment.id+">"+value.marketSegment.title+"</option>";
                    });
                }
            }
            $('#market_segment_id').html(str);
        },function(result){
            alert('Fail');
        });
    }


    $("body").on("change", "#market_id",function(event){
        var market_id = parseInt($('#market_id').val());
        var data = {market_id : market_id};
        getMarketSegments(data);
       
    });
    
  

    
</script>

