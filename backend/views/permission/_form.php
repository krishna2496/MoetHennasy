<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Permissions */
/* @var $form yii\widgets\ActiveForm */
?>

<h2><?php echo $this->title;?></h2>
<div class="permission_create"><br>
    <?php $form = ActiveForm::begin(['id' => 'frm-permissionCreate', 'options' => ["class" => "form-horizontal form-label-left", 'validateOnChange' => true, 'validateOnBlur'=> false]]); ?>

    <?= $form->field($model, 'permission_label')->textInput(['maxlength' => true,'autofocus'=>true]) ?>

    <?= $form->field($model, 'permission_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList($listPermissions); ?>

    <div class="form-group">
   	    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
	        <?= Html::submitButton($model->isNewRecord ?  'Create' :  'Submit' , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	        <?= Html::a('Cancel',  ['permission/index'], ['class'=>'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
 </div>
