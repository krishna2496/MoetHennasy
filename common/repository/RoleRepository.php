<?php
namespace common\repository;
use common\models\Role;
use Yii;

class RoleRepository extends Repository
{
    public function listing($filter = array())
    {       
        $this->apiCode = 1;
        $data = array();
        if(!empty($filter) && isset($filter) && (isset($filter['from_dashboard'])) && ($filter['from_dashboard'] == 1)){
        $data['roles'] = Role::find()->orderBy(['title' => yii::$app->params['defaultSorting']])->asArray()->all(); 
        }else{
        $data['roles'] = Role::find()->andWhere(['!=', 'id', Yii::$app->params['superAdminRole']])->asArray()->all();
        }
        $this->apiData = $data;
        return $this->response();
    }
}