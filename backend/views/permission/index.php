<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\CommonHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PermissionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= Html::encode($this->title) ?> </h2>
<?php  if(CommonHelper::checkPermission('Permissions.Create')){ ?>
<?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
<?php } ?>
<?php 
//hide and unhide action coloumn based on conditions
if (CommonHelper::checkPermission('Permissions.View') || CommonHelper::checkPermission('Permissions.Update') || CommonHelper::checkPermission('Permissions.Delete')) {
        $isActionColoum=1; // return true;
} else {
        $isActionColoum=0; // return false;
}?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout'=>"{items}{pager}{summary}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'permission_label',
        'permission_title',
        [
            'class' => 'yii\grid\ActionColumn',
            'visible' =>$isActionColoum,
            'header'=> 'Action',
            'headerOptions' => [
                'style' => 'color:#004FA3'
            ],
            'template' => '{update} {delete}', 
            'buttons'=>[
                'update'=>function ($url,$model) { 
                    if(CommonHelper::checkPermission('Admin.Permissions.Update')){
                        
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/permission/update/'.$model['id']]);
                    } 
                },
                'delete'=>function ($url,$model) { 
                    if(CommonHelper::checkPermission('Admin.Permissions.Delete')){

                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/permission/delete/'.$model['id']],['data-method'=>'post','data-confirm'=>Yii::t("app", "delete_confirm"), 'title' => 'Delete']);
                    } 
                },
            ],
        ],
    ],
]); 
?>