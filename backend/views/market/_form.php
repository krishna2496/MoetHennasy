<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;

/* @var $this yii\web\View */
/* @var $model common\models\MarketSegments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="market-segments-form">
    <div class="row">
        <div class="col-xs-12">
            <?php $form = ActiveForm::begin(); ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'title')->textInput(['autofocus' => 'autofocus']) ?>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'market_segment_id')->dropDownList($marketSegmentList,['multiple'=>'multiple','class'=>'form-control select2']); ?>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" align="center">
                <div class="col-xs-6">
                    <?php if (isset($model->id) && $model->id) { ?>
                        <?= Html::a('Cancel', ['market/index'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } else { ?>
                        <?= Html::a('Reset', ['market/create'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } ?>
                </div>
                <div class="col-xs-6">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary pull-left mw-md']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() 
    {
        $('.select2').select2({
            placeholder: "Select Market Cluster" 
        });
    });
    </script>
