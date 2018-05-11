<?php
 
namespace common\components; 
use Yii;
use common\models\User;
use common\helpers\CommonHelper;

class Activity extends \yii\filters\AccessRule {
 
    protected function matchRole($user)
    {
       echo 'zaf';exit; 
    }
}