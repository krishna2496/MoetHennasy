<?php
use yii\helpers\Html;
use common\widgets\Alert;
use common\helpers\CommonHelper;
use backend\assets\AuthAsset;

AuthAsset::register($this);
$this->registerAssetBundle(yii\web\JqueryAsset::className(), \yii\web\View::POS_HEAD);
$logedInUser = CommonHelper::getUser();
$apiUrl = CommonHelper::getPath('api_url');
$adminUrl = CommonHelper::getPath('admin_url');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition login-page">
        <?php $this->beginBody() ?>
        <div class="col-md-4 left-part">
            <div class="logo-top">
              <img src="<?php echo $adminUrl; ?>images/logo-login.jpg" alt="MH">
            </div>
            <img src="<?php echo $adminUrl; ?>images/login-image.jpg" alt="Moët Hennessy" class="log-img">
        </div>
        <div class="col-md-8 right-part">
            <div id="flash-message-block"></div>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
        <?php $this->endBody() ?>
        <script type="text/javascript">
            var appUrl = '<?php echo $apiUrl; ?>';
            var adminUrl = '<?php echo $adminUrl; ?>';
            var deviceType = {'ios':1, 'android':2, 'web':3};
        </script>
    </body>
</html>
<?php $this->endPage() ?>
