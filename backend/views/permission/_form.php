<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="permissions-create">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <div class="box-body">
                    <?php $form = ActiveForm::begin(['id' => 'frm-permissionCreate', 'options' => ['validateOnChange' => true, 'validateOnBlur'=> false]]); ?>

                    <?= $form->field($model, 'permission_label')->textInput(['maxlength' => true,'autofocus'=>true]) ?>

                    <?= $form->field($model, 'permission_title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'parent_id')->dropDownList($listPermissions); ?>

                    <div class="form-group">
                   	    <?= Html::submitButton($model->isNewRecord ?  'Create' :  'Submit' , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <?= Html::a('Cancel',  ['permission/index'], ['class'=>'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
             </div>
        </div>
    </div>
</div>

