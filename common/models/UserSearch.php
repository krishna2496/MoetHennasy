<?php

namespace common\models;

use Yii;
use yii\data\ArrayDataProvider;
use common\models\User;
use yii\base\Model;
use common\repository\UserRepository;

class UserSearch extends User {

    public function rules() {
        return [
                [['id', 'created_by', 'updated_by', 'deleted_by', 'status'], 'integer'],
                [['username', 'name', 'first_name', 'last_name', 'email', 'role_id','parent_user_id'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $userRepository = new UserRepository;
        $userList = array();
        $resultUserList = $userRepository->userList($params);
        if($resultUserList['status']['success'] == 1){
            if($resultUserList['data']['users']){
                foreach ($resultUserList['data']['users'] as $key => $value) {
                    $temp = $value;
                    $temp['name'] = $value['first_name'].' '.$value['last_name'];
                    $temp['role'] = isset($value['role']['title']) ? $value['role']['title'] : '';
                    $userList[] = $temp;
                }
            }
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $userList,
            'pagination' => [
                'pageSize' => $params['limit']
            ],
            'sort' => [
                'attributes' =>
                [
                    'username',
                    'email',
                    'name',
                    'role',
                ],
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ]
        ]);

        return $dataProvider;
    }

}
