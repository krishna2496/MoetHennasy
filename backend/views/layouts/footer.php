<?php 
use common\helpers\CommonHelper;
?>
<footer class="main-footer">
    <strong>Version : <?= Yii::getVersion(); ?> <span> | </span>Copyright &copy; <?php echo date('Y')?> <a href="<?= CommonHelper::getPath('base_url')?>"><?= Yii::$app->name;?></a>.</strong> All rights reserved.
</footer>