<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Reset Password';
$loginUrl = Url::to(['site/login']);
?>
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0)" class="welcome"></a>
        <p>
            <?= Html::encode($this->title) ?> <br>
            <span>Please choose your new password.</span>
        </p>
    </div>
    <div class="login-box-body">
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form','fieldConfig' => ['options' => ['class' => 'form-group has-feedback']]]); ?>
            <?= $form->field($model, 'password',['template' => '{label}{input}{error}<span class="glyphicon glyphicon-lock form-control-feedback"></span><a href="'.$loginUrl.'" class="forgot">Login here</a><div class="clearfix"></div>'])->passwordInput(['autofocus' => true,'placeholder' =>'Password'])->label('PASSWORD') ?>
            <?= $form->field($model, 'token')->hiddenInput(['value'=>$token])->label(false) ?>
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
        <div class="copyright">
          <span>© 2018 - DIYS by ODiTY</span>
        </div>
    </div>
</div>

