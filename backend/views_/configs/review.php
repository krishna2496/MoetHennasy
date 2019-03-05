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
            echo Html::hiddenInput('task_form_type', 'task-details');
             $items = [[
                    'label' => 'Ratings',
                    'headerOptions' => ['id' => 'li-task-details'],
                    'options' => ['id' => 'rating'],
                    'content' => $this->render('rating-form', [
                        'model' =>$model,
                        'store_id'=>$store_id,
                        'config_id' =>$config_id,
                        'totalCount' => $totalCount
                    ]), 
                ],
                    [
                    'label' => 'Questions',
                    'headerOptions' => ['id' => 'li-questions'],
                    'options' => ['id' => 'questions'],
                    'content' => $this->render('questions', [
                        'model' =>$questionsModel,
                        'responseData'=>$data,
                        'store_id'=>$store_id,
                        'config_id' =>$config_id,
                        'questions' =>$questions
                    ]), 
                  
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
<script type="text/javascript">
    $(".nav-tabs a[data-toggle=tab]").on("click", function (e) {
        if ($(this).hasClass("disabled")) {
            e.preventDefault();
            return false;
        }
    });
</script>
