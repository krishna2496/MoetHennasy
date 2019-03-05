<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Permissions */

$this->title = 'Update Permission'.' : ' . $model->permission_title;
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<?= $this->render('_form', [
    'model' => $model,
    'listPermissions' => $listPermissions,
]) ?>
