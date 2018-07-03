<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\helpers\CommonHelper;

AppAsset::register($this);
$this->registerAssetBundle(yii\web\JqueryAsset::className(), \yii\web\View::POS_HEAD);
$this->registerAssetBundle(backend\assets\MoetAsset::className(), \yii\web\View::POS_HEAD);
$logedInUser = CommonHelper::getUser();
$apiUrl = CommonHelper::getPath('api_url');
$adminUrl = CommonHelper::getPath('admin_url');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">  
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <div class="page-loader">
                <img src="<?php echo Yii::$app->request->baseUrl.'/images/loader.gif'; ?>">
            </div>
            <?php echo $this->render('navbar'); ?>

            <?php echo $this->render('menubar'); ?>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                       Moët Hennessy
                    </h1>
                    <?php 
                        echo Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]);
                    ?>
                </section>
                <section class="content">
                    <div id="flash-message-block"></div>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </section>
            </div>

            <?php echo $this->render('footer'); ?>

            <div class="control-sidebar-bg"></div>
        </div>
        <?php $this->endBody() ?>
        <script type="text/javascript">
            var appUrl = '<?php echo $apiUrl; ?>';
            var adminUrl = '<?php echo $adminUrl; ?>';
            var rackWidthValue = '<?php echo yii::$app->params['rackWidth'][0] ?>';
            var deviceType = {'ios':1, 'android':2, 'web':3};
        </script>
    </body>
</html>
<?php $this->endPage() ?>
