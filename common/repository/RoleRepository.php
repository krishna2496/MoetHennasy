<?php
namespace common\repository;
use common\models\Role;
use Yii;

class RoleRepository extends Repository
{
    public function listing()
    {   
        $this->apiCode = 1;
        $data = array();
        $data['roles'] = Role::find()->andWhere(['!=', 'id', Yii::$app->params['superAdminRole']])->asArray()->all();
        $this->apiData = $data;
        return $this->response();
    }
}