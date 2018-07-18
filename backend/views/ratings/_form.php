<style>
    .icon{
        font-size:30px;
        color: rgb(255, 134, 0);
    }
</style>
<?php
//echo '<pre>';
//print_R($model);exit;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
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

                            <label>    
                                <?php 
                                if (isset($model->id) && $model->id) {
                                   $count=  $model->rating;
                                }else{
                                $model->rating = $count ;
                                }
                                ?>
                                <?php 

                                if($count > yii::$app->params['star_max_size'][0]){
                                  
                                    $count = yii::$app->params['star_max_size'][0];
                                    $model->rating = $count ;
                                }
                                ?>
                                <?= $form->field($model, 'rating')->hiddenInput(['maxlength' => true]) ?>
                             
                                <?php
                                for ($i = 1; $i <= $count; $i++) {
                                    ?>
                                    <span class="icon" data-rate="1">★</span>
<?php } ?>
                            </label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
<?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" align="center">
                <div class="col-xs-6">
                    <?php if (isset($model->id) && $model->id) { ?>
                        <?= Html::a('Cancel', ['ratings/index'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
                    <?php } else { ?>
                        <?= Html::a('Reset', ['ratings/create'], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
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
<script>

</script>
