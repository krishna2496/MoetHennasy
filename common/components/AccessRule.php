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
        $authKey = isset($_COOKIE['auth_key']) ? $_COOKIE['auth_key'] : '';
        $matchFlag = (User::findIdentityByAccessToken($authKey)) ? true : false;
        foreach ($this->roles as $role) {

            switch ($role) {
                case '?':
                    $canAccess = ($matchFlag) ? false : true;
                    break;

                case '@':
                    $canAccess = $matchFlag;
                    break;

                case '&':
                    $controller    = \Yii::$app->controller;
                    $controllerId  = $controller->id;
                    $actionId      = $controller->action->id;
                    
                    // Genrate current permission values
                    $permissions = array();
                    $permissions[] = implode('.', array('admin', $controllerId, $actionId));
                    
                    // Check for camelcase permission
                    if(substr_count($controllerId, '-') > 0) {
                        $controllerId = lcfirst(str_replace("- ", "", ucwords(str_replace("-", "- ", $controllerId))));
                        $permissions[] = implode('.', array('admin', $controllerId, $actionId));
                    }

                    // Check permission
                    $canAction = CommonHelper::checkPermission($permissions);

                    if ($matchFlag && $canAction) {
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