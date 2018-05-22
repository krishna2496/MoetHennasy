<style>
    .box.box-solid.box-warning > .box-header {
        color: #fff;
        background: #7a603a;
        background-color: #7a603a;
    }
    .box.box-solid.box-warning {
        border: 1px solid #7a603a;
    }.box-body {
        border-radius: 0 0 7px 9px;
        padding: 24px;
    }
</style>
<?php

use yii\helpers\Html;
use common\helpers\CommonHelper;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

$formUrl = 'configs/create-question/';
if ($model->id) {
    $formUrl = 'configs/create-question/' . $model->id;
}
//echo '<pre>';
//print_r($questions);exit;
?>
<div class="stores-create">

    <div class="market-segments-form">
        <div class="row">
            <div class="col-xs-12">
                <?php
                $form = ActiveForm::begin(['action' => [$formUrl], 'options' => [
                            'tag' => 'div',
                            'class' => 'form-group ',
                            'id' => 'StarRating'
                ]]);
                ?>
                <div class="box">
                    <div class="box-header">

                        <?php
                        if (isset($questions) && is_array($questions)) {
                            foreach ($questions as $i => $value) {
                                ?>
                                <div class="box box-warning box-solid"> 
                                    <div class="box-header with-border">
                                        <h3 class="box-title"></h3>

                                        <?= $value->question ?>

                                    </div>  
                                    <div class="box-body" style="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php if (Yii::$app->params['response_type'][$value->response_type] == Yii::$app->params['response_type'][0]) { ?>
                                                    <?= $form->field($value, "[$i]response_type", ['template' => '{input}{error}'])->dropDownList(Yii::$app->params['catalogue_status'], ['prompt' => 'Select Status', 'autofocus' => true, 'class' => 'form-control']) ?>      
                                                <?php } else if (Yii::$app->params['response_type'][$value->response_type] == Yii::$app->params['response_type'][1]) { ?>
                                                    <?= $form->field($value, "[$i]response_type", ['template' => '{input}{error}'])->dropDownList(Yii::$app->params['catalogue_status'], ['prompt' => 'Select Status', 'autofocus' => true, 'class' => 'form-control']) ?>
                                                <?php } else if (Yii::$app->params['response_type'][$value->response_type] == Yii::$app->params['response_type'][3]) { ?>
                                                    <?= $form->field($value, "[$i]response_type", ['template' => '{input}{error}'])->textarea(['value' => '', 'maxlength' => true, 'class' => 'form-control']) ?>
                                                <?php } ?>

                                                <?= $form->field($value, "[$i]id", ['template' => '{input}{error}'])->hiddenInput(['value' => $value->id, 'maxlength' => true]) ?>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                <?php
                            }
                        }
                        ?>
                         <input type="hidden" value=<?= $store_id ?> name="store_id" id="store_id"/>
                                <input type="hidden" value=<?= $config_id ?> name="config_id" id="config_id"/>
                        <div class="row">
                            <div class="col-xs-2 col-md-2">
                                   <?= Html::submitButton('Save', ['class' => 'btn btn-primary pull-right mw-md','id'=>'create']) ?>
                            </div>
                            <div class="col-xs-4 col-md-4">
                                   <?= Html::Button('Next', ['class' => 'btn btn-inverse pull-left mw-md','id'=>'next']) ?>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>     
        </div>

    </div>
</div>


<?php ActiveForm::end(); ?>
<script>

    $('#next').click(function (e) {
        $(".li-questions").tabs("enable");
    });
</script>
