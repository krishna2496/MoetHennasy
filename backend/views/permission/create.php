<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Permissions */

$this->title = 'Create Permission';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'listPermissions' => $listPermissions,
]) ?>