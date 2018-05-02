<?php

use yii\helpers\Html;
$this->title = 'Update User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'parentUserClass' => $parentUserClass,
        'userList' => $userList,
    ]) ?>

</div>
