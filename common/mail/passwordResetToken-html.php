<?php
use yii\helpers\Html;
use common\helpers\CommonHelper;

$resetLink = CommonHelper::getPath('admin_url').'site/reset-password?token='.$user->password_reset_token;
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
