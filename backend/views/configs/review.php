<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\CommonHelper;
use yii\bootstrap\Tabs;

$this->title = 'Review';
//$this->params['breadcrumbs'][] = ['label' => 'Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configs-create">
    <div class="catalogues-form">
    <div class="row">
        <div class="col-xs-12">
          
            <?php
             $items = [[
                    'label' => '<i class="fa text-yellow fa-star lg"></i> Ratings',
                    'headerOptions' => ['id' => 'li-task-details'],
                    'options' => ['id' => 'task-details'],
                    'content' => $this->render('rating-form', [
                        'model' =>$model,
                        'store_id'=>$store_id,
                        'config_id' =>$config_id,
                    ]),
                    'active' =>TRUE
                ],
                    [
                    'label' => 'Questions',
                    'options' => ['id' => 'processing-information'],
                    'content' =>'hkghk'
                  
                ],
                ];
             
        
           ?>
<div class="client-create">
    <div class="client-index task-header clearfix">
    
        <div class="task-header-buttons clearfix"></div>
    </div>
    <div class="tasks-create widget">
        <?=
        Tabs::widget([
            'encodeLabels' => false,
            'tabContentOptions' => [
                'class' => 'client-form master-form'
            ],
            'items' => $items
            ]);
        ?>
    </div>
</div>
        </div>
    </div>
</div>


</div>
