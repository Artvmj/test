<?php
namespace app\modules\decor\models;

use Yii;
use yii\easyii\behaviors\CacheFlush;

class Decor extends \yii\easyii\components\ActiveRecord
{
    const CACHE_KEY = 'easyii_text';

    public static function tableName()
    {
        return 'app_decors';
    }

    public function rules()
    {
        return [
            ['text_id', 'number', 'integerOnly' => true],
            ['name', 'required'],
            ['price', 'required'],
            ['price', 'number', 'integerOnly' => true],
            ['name', 'trim'],
         //   ['slug', 'match', 'pattern' => '/^[0-9,]{0,11128}$/', 'message' => Yii::t('easyii', 'Значения по умолчанию должны содержать только цифры и запятые')],
         //  ['slug', 'unique']
        ];
    }

    
    
     public function getValue()
   {
       return $this->hasMany(Decor::className(), ['text_id' => 'decor_id'])
          ->viaTable('sport_has_decor', ['sport_id' => 'text_id']);
   }
    
 
    
    
    
    public function attributeLabels()
    {
        return [
            'name' =>  "Название",
            'ed' =>  "Единица измерения",
            'price' =>  "Цена",
            'slug' =>  "Символьный код",
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className()
        ];
    }
}