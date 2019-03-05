<?php

use yii\helpers\Html;
use common\helpers\CommonHelper;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
$formUrl ='configs/create-rating/';
if ($model->id) {
    $formUrl = 'configs/update-rating/'.$model->id;
}
?>
<div class="stores-create">

    <div class="market-segments-form">
        <div class="row">
            <div class="col-xs-12">
                <?php
                $form = ActiveForm::begin(['action' => [$formUrl],'options' => [
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

<?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <script>

        $(document).ready(function () {
var stars='<?php echo $totalCount; ?>';
            $("#ratings-rating").rating({min: 0, max: stars, step: 1, stars: stars, size: 'xs'});

        });
        $('#next').click(function(e){
         $( ".li-questions" ).tabs( "enable" );
        });
    </script>
</div>
