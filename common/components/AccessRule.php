<?php
 
namespace common\components; 
use Yii;
use common\models\User;
use common\helpers\CommonHelper;

class AccessRule extends \yii\filters\AccessRule {
 
    /**
     * @inheritdoc
     */
    protected function matchRole($user)
    {
        if (empty($this->roles)) {
            return true;
        }

        $canAccess = false;
        
        foreach ($this->roles as $role) {

            switch ($role) {
                case '?':
                    if ($user->getIsGuest()) {
                        $canAccess = true;
                    }
                    break;

                case '@':
                    if (!$user->getIsGuest()) {
                        $canAccess = true;
                    }
                    break;

                case '&':
                    $controller    = \Yii::$app->controller;
                    $controllerId  = $controller->id;
                    $actionId      = $controller->action->id;
                    
                    // Genrate current permission values
                    $permissions = array();
                    $permissions[] = implode('.', array($controllerId, $actionId));
                    
                    // Check for camelcase permission
                    if(substr_count($controllerId, '-') > 0) {
                        $controllerId = lcfirst(str_replace("- ", "", ucwords(str_replace("-", "- ", $controllerId))));
                        $permissions[] = implode('.', array($controllerId, $actionId));
                    }

                    // Check permission
                    $canAction = CommonHelper::checkPermission($permissions,'api');

                    if (!$user->getIsGuest() && $canAction) {
                        $canAccess = true;
                    }

                    break;
                
                default:
                    if ($user->can($role)) {
                        $canAccess = true;
                    }
                    break;
            }
        }

        return $canAccess;
    }
}