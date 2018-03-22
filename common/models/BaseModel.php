<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\CommonHelper;

class BaseModel extends ActiveRecord
{
    public static function find()
    {
        $alias = static::tableName();
        return parent::find()->where(["{$alias}.deleted_at" => NULL]);
    }
    
    public function delete()
    {	
        if (!$this->beforeDelete()) {
                return false;
        }

        if($this->canDelete()){
            $this->deleted_by = CommonHelper::getUser()->id;
            $this->deleted_at = date('Y-m-d H:i:s');
            $this->save(false);
            
            $this->afterDelete();

            return true;
        }

        return false;
    }
    
    public static function deleteAll($condition = array(),$hardDelete = false)
    {
        if($hardDelete == false){
                if(!empty($condition)){
                        $models = static::findByCondition($condition)->all();
                } else {
                        $models = static::find()->all();
                }

                foreach ($models as $model) {
                        if (!$model->beforeDelete()) {
                                return false;
                        }
                }

                $columns = array();
                 
                $columns['deleted_by'] = isset(CommonHelper::getUser()->id) ? CommonHelper::getUser()->id : 0;
                $columns['deleted_at'] = date('Y-m-d H:i:s');

                $command = static::getDb()->createCommand();
                $command->update(static::tableName(), $columns, $condition);
                $command->execute();

                foreach ($models as $model) {
                        $model->afterDelete();
                }
        }
        else{
                $command = static::getDb()->createCommand();
                $command->delete(static::tableName(), $condition);
                return $command->execute();
        }
    }
}
