<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;
use common\helpers\CommonHelper;
use common\models\ActionLog;
/**
 * Base controller
 */
class BaseController extends Controller
{
	public function __construct($id, $module, $config = [])
	{

		parent::__construct($id, $module, $config);	
	}
        
        protected function userActivity($actionId = '',$descrition='') {
        $currentUser = CommonHelper::getUser();

           $actionLog = new ActionLog();
           $actionLog->action_type=$actionId;
           $actionLog->date=date('Y-m-d');
           $actionLog->time=date('H:m:s');
           if($descrition != ''){
           $actionLog->description=$descrition;
           }else{
             $actionIds=  explode('_', $actionId);
             $descrition= implode(' ', $actionIds);
             $actionLog->description=$descrition;
           }
           $actionLog->user=$currentUser['id'];
           if($actionLog->validate()){
               $actionLog->save(false);
           } 
    }
}
