<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;
use yii\bootstrap\Carousel;
use yii\widgets\ActiveForm;

?>


<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
<?= Html::encode($this->title) ?>
                </h3>
                <div class="row pull-right">

                </div>
            </div>
            <div class="box-body">
                <div class="row">
                   
              <?php
            
             
              foreach ($dataProvider as $key=>$value){
                
                  if($value['image'] != ''){
                  echo '<div class="col-md-1"><img style="height:100px;width:100px" src="'. CommonHelper::getImage(UPLOAD_PATH_CATALOGUES_IMAGES . $value['image']).'" alt=""></img></div>';
              }
              
                  }
              ?>
                </div>
                   <?php $form = ActiveForm::begin(['action' => ['rule-administration/index'], 'method' => 'post', 'options' => ['data-pjax' => '', 'id' => 'w2']]); ?>
        <div class="row">
            <?php if (isset($filters['market_id'])) { ?>
                <input type="hidden" value="<?= $filters['market_id'] ?>" name="market_id"/>
                <?php
            }
            if (isset($filters['brand_id']) && ($filters['brand_id'] != '')) {
                $brand = implode(',', $filters['brand_id']);
                ?>
                <input type="hidden" value="<?= $brand ?>" name="brand_id"/>
                <?php
            }
            if (isset($filters['product_category_id'])) {
                ?>
                <input type="hidden" value="<?= $filters['product_category_id'] ?>" name="product_category_id"/>
                <?php
            }
            if (isset($filters['market_cluster_id'])) {
                ?>
                <input type="hidden" value="<?= $filters['market_cluster_id'] ?>" name="market_cluster_id"/>
            <?php } ?>
               <?php 
                if (isset($filters['selection']) && ($filters['selection'] != '')) {
                $selection = implode(',', $filters['selection']);
                ?>
                <input type="hidden" value="<?= $selection ?>" name="selection" id="selection"/>
                <?php
             }
               ?> 
         
            <div class="col-md-12 isDisplay">
                <?= Html::Button('Auto Fill', ['class' => 'btn btn-primary pull-left mw-md auto_fill', 'style' => 'margin-top:25px']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(".auto_fill").on('click', function () {
            $("#w2").submit();
           
        });
    </script>
