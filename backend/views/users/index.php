<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;
use yii\helpers\Url;

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
$formUrl = Url::to(['users/index']);
if (isset($filters['setParentID'])) {
    $formUrl = Url::to(['users/index/' . $filters['parent_user_id']]);
}
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?= Html::encode($this->title) ?>
                </h3>
                <div class="row ">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-10">
                        <?= Html::beginForm($formUrl, 'get', ['data-pjax' => '', 'id' => 'search-users']); ?>
                        <div class="filter-search dataTables_filter clearfix">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php if (CommonHelper::checkPermission('Users.Create')) { ?>
                                        <?= Html::a('Add User', ['create'], ['class' => 'btn btn-primary']) ?>
                                    <?php } ?>
                                </div>
                                <?php if(Yii::$app->user->identity->role_id == 1){?>
                                <div class="col-md-3">
                                    <?php echo Html::dropDownList('role_id', isset($filters['role_id']) ? $filters['role_id'] : '', $roles, ['class' => 'form-control select2', 'id' => 'user-type', 'prompt' => 'Select User Type']) ?>
                                </div>
                                <?php } ?>
                                <div class="col-md-2">
                                    <?= Html::input('text', 'search', isset($filters['search']) ? $filters['search'] : '', ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'user-text']) ?>
                                </div>

                                <div class="col-md-3">
                                    <?= Html::dropDownList('market_id', isset($filters['market_id']) ? $filters['market_id'] : '', $markets, ['class' => 'form-control select2', 'id' => 'user-market', 'prompt' => 'Select Market']) ?>
                                </div>
                                <div class="col-md-2">
                                    <?= Html::dropDownList('limit', isset($filters['limit']) ? $filters['limit'] : '', Yii::$app->params['limit'], ['class' => 'form-control', 'id' => 'user-limit']) ?>
                                </div>
                            </div>
                        </div>
                        <?= Html::endForm(); ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '<div class="table-responsive">{items}</div><div class="row"><div class="col-sm-5">{summary}</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                    'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        'username',
                        'email:email',
                            [
                            'label' => 'User Type',
                            'attribute' => 'role',
                        ],
                            [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => '{view} {update} {delete} {manageUser}',
                            'buttons' => [
                                'view' => function ($url, $model) use ($filters) {
                                    $addLink = isset($filters['setParentID']) ? '/' . $model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['users/view/' . $model['id'] . $addLink], ['title' => 'View']);
                                },
                                'update' => function ($url, $model) use ($filters) {
                                    $addLink = isset($filters['setParentID']) ? '/' . $model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['users/update/' . $model['id'] . $addLink], ['title' => 'Update']);
                                },
                                'delete' => function ($url, $model) use ($filters) {
                                    $addLink = isset($filters['setParentID']) ? '/' . $model['parent_user_id'] : '';
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['users/delete/' . $model['id'] . $addLink], ['data-method' => 'post', 'data-confirm' => 'Are you sure want to delete this user?', 'title' => 'Delete']);
                                },
                                'manageUser' => function ($url, $model) use ($hasChild) {
                                    if ($hasChild) {
                                        return Html::a('<span class="glyphicon glyphicon-user"></span>', ['users/index/' . $model['id']], ['title' => 'Manager Users']);
                                    }
                                },
                            ],
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("body").on("change", "#user-type,#user-text,#user-limit,#user-market", function (event) {
        $('#search-users').submit();
    });
</script>