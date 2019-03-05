<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Login';
$adminUrl = CommonHelper::getPath('admin_url');
$forgotUrl = Url::to(['site/request-password-reset']);
?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo $adminUrl; ?>" class="welcome">Welcome</a>
        <p>
            Sign in to your account <br>
            <span>Please enter your name and password to log in.</span>
        </p>
    </div>
    <div class="login-box-body">
        <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => ['options' => ['class' => 'form-group has-feedback']]]); ?>

            <?= $form->field($model, 'username',['template' => '{label}{input}{error}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>'])->textInput(['autofocus' => true,'placeholder' =>'Username'])->label('USERNAME') ?>

            <?= $form->field($model, 'password',['template' => '{label}{input}{error}<span class="glyphicon glyphicon-lock form-control-feedback"></span><a href="'.$forgotUrl.'" class="forgot">I forgot my password</a><div class="clearfix"></div>'])->passwordInput(['placeholder' =>'Password'])->label('PASSWORD') ?>
            <div class="row">
                <div class="col-xs-8">
                  <div class="checkbox icheck">
                    <label>
                      <?= $form->field($model, 'rememberMe', ['template' => "{input}",'options' => ['tag'=>false]])->checkbox(['id' => 'checkbox-demo-3','tabindex' => '3'], false)->label()?> Keep me signed in
                    </label>
                  </div>
                </div>
                <div class="col-xs-4">
                  <?= Html::submitButton('LOGIN', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button', 'id'=>'login-button']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
        <div class="copyright">
          <span>© 2018 - DIYS by ODiTY</span>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' /* optional */
        });
    });
</script>
