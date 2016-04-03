<?php
namespace app\modules\color\models;

use Yii;
use yii\easyii\behaviors\SeoBehavior;

class Color extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'app_colors';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
           // [['title', 'string'], 'trim'],
            ['title', 'string', 'max' => 128], 
            ['hex', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' =>  "Название", 
            'hex' =>  "Код",
        ];
    }

    public function behaviors()
    {
        return [
            'seoBehavior' => SeoBehavior::className(),
        ];
    }
}