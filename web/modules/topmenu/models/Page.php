<?php
namespace app\modules\topmenu\models;

use Yii;
use yii\easyii\behaviors\SeoBehavior;

class Page extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'app_topmenus';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
           
            ['title', 'string', 'max' => 128],
             
               [[  'time' ], 'integer'],
            
            ['slug', 'match', 'pattern' =>  '/[^p{Cyrillic}]$/'  ,  
                'message' => Yii::t('easyii', 'Ссылка не должна содержать кириллицу')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            'seoBehavior' => SeoBehavior::className(),
        ];
    }
}