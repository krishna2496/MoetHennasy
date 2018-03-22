<?php
use common\helpers\CommonHelper;

$resetLink = CommonHelper::getPath('api_admin_url').'site/reset-password?token='.$user->password_reset_token;
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
