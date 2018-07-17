<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "province".
 *
 * @property int $id
 * @property string $name
 * @property int $city_id
 */
class Province extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'city_id' => 'City ID',
        ];
    }
}
