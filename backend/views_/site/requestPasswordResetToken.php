<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Forgot Password';
$adminUrl = CommonHelper::getPath('admin_url');
$loginUrl = Url::to(['site/login']);
?>
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0)" class="welcome"></a>
        <p>
            <?= Html::encode($this->title) ?> <br>
            <span>Please fill out your email. A link to reset password will be sent there.</span>
        </p>
    </div>
    <div class="login-box-body">
        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form','fieldConfig' => ['options' => ['class' => 'form-group has-feedback']]]); ?>
            <?= $form->field($model, 'email',['template' => '{label}{input}{error}<span class="glyphicon glyphicon-envelope form-control-feedback"></span><a href="'.$loginUrl.'" class="forgot">Login here</a><div class="clearfix"></div>'])->textInput(['autofocus' => true,'placeholder' =>'Email'])->label('EMAIL')  ?>
            <div class="form-group">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
        <div class="copyright">
          <span>© 2018 - DIYS by ODiTY</span>
        </div>
    </div>
</div>
