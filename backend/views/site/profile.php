<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
$this->title = 'Update Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-create">
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
                			<?= $form->field($model, 'username')->textInput(['autofocus' => 'autofocus']) ?>
                		</div>
                		<div class="col-md-6">
                			<?= $form->field($model, 'email')->textInput(['disabled'=>true]) ?>
                		</div>
                	</div>
                	<div class="row">
                		<div class="col-md-6">
                			<?= $form->field($model, 'first_name')->textInput() ?>
                		</div>
                		<div class="col-md-6">
                			<?= $form->field($model, 'last_name')->textInput() ?>
                		</div>
                	</div>
                	<?php if(isset($model->id) && $model->id) { ?>
                	<div class="row">
                		<div class="col-md-6">
                			<?= $form->field($model, 'new_password')->passwordInput() ?>
                		</div>
                		<div class="col-md-6">
                			<?= $form->field($model, 'confirm_password')->passwordInput() ?>
                		</div>
                	</div>
                	<?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'address')->textarea(['rows' => '6']); ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'phone')->textInput(); ?> 
                        </div>
                    </div>
                	<div class="row">
                		<div class="col-md-6">
                			<div class="row">
                                <div class="col-md-8">
                                    <?= $form->field($model, 'userImage')->fileInput() ?>  
                                </div>
                                <?php if(isset($model->id) && $model->id) { ?>
                                <div class="col-md-2">
                                    <img class="img-responsive" src="<?php echo CommonHelper::getImage(UPLOAD_PATH_USER_IMAGES . $model->profile_photo); ?>"/>        
                                </div>
                                <?php } ?>
                            </div>
                		</div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'company_name')->textInput(); ?> 
                        </div>
                	</div>
                </div>
            </div>
            <div class="row" align="center">
                <div class="col-xs-6">
                	<?php if(isset($model->id) && $model->id) { ?>
                		<?= Html::a('Cancel',  ['users/index'], ['class'=>'btn pull-right mw-md btn-inverse']) ?>
                	<?php } else { ?>
                		<?= Html::a('Reset',  ['users/create'], ['class'=>'btn pull-right mw-md btn-inverse']) ?>
                	<?php } ?>
                </div>
                <div class="col-xs-6">
                  <?= Html::submitButton('OK', ['class' => 'btn btn-primary pull-left mw-md']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
