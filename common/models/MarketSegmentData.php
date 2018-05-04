<?php

namespace common\models;

use Yii;
use common\models\MarketSegments;
/**
 * This is the model class for table "market_segment_data".
 *
 * @property int $id
 * @property int $market_id
 * @property int $market_segment_data
 */
class MarketSegmentData extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'market_segment_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['market_id', 'market_segment_id'], 'required','on'=>['create','update']],
            [['market_id', 'market_segment_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'market_id' => 'Market ID',
            'market_segment_id' => 'Market Segment',
        ];
    }
    public function getMarketSegment(){
        return $this->hasOne(MarketSegments::className(), ['id' => 'market_segment_id']);
    }
}
