<?php

use yii\helpers\Html;
use common\helpers\CommonHelper;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
$formUrl = Url::to(['configs/create-rating/']);
if ($model->id) {
    $formUrl = Url::to(['configs/create-rating/']);
}
?>
<div class="stores-create">

    <div class="market-segments-form">
        <div class="row">
            <div class="col-xs-12">
                <?php
                $form = ActiveForm::begin(['action' => ['configs/create-rating'],'options' => [
                            'tag' => 'div',
                            'class' => 'form-group ',
                            'id' => 'StarRating'
                ]]);
                ?>
                <div class="box">
                    <div class="box-header">

                    </div>
                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-6">
<?= $form->field($model, 'reviews')->textInput(['maxlength' => true, 'id' => 'ratings-rating']) ?>
                                <input type="hidden" value=<?= $store_id ?> name="store_id" id="store_id"/>
                                <input type="hidden" value=<?= $config_id ?> name="config_id" id="config_id"/>
                            </div>

                        </div>

                        <div class="row" align="center">
                            <div class="col-xs-6">
                                <?php if (isset($model->id) && $model->id) { ?>
                                    <?= Html::a('Save', ['configs/updateRating'.$config_id.'/'.$store_id], ['class' => 'btn pull-right mw-md btn-inverse']) ?>
<?php } else { ?>
                                   <?= Html::submitButton('Save', ['class' => 'btn btn-info btn-md','id'=>'create']) ?>
                                   
                                <?php } ?>
                            </div>
                          
                        </div>

                    </div>
                </div>

<?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <script>

        $(document).ready(function () {

            $("#ratings-rating").rating({min: 0, max: 3, step: 1, stars: 3, size: 'xs'});

        });
    </script>
</div>
