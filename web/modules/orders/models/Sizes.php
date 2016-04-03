<?php
namespace app\modules\orders\models; 
use app\modules\cat\models\Item; 
use Yii;
use yii\easyii\validators\EscapeValidator;
use app\modules\sizes\models\Sizes as Sizesitems;


class Sizes extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'app_orders_sizes';
    }

    public function rules()
    {
        return [
            [['good_id' ], 'required'],
            ['name', 'trim'],
            [['good_id' , 'count'], 'integer', 'min' => 1], 
            ['count', 'default', 'value' => 1],
           
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function getSize()
    {
        return $this->hasOne(Sizesitems::className(), ['good_id' => 'text_id']); 
    }

    public function getGood()
     {
        return $this->hasOne(Good::className(), ['good_id' => 'order_id']);
     }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

    }
}