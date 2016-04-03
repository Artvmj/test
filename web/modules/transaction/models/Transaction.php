<?php
namespace app\modules\slider\models;

use Yii; 

class Transaction extends \yii\db\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const CACHE_KEY = 'app_slider';

    public static function tableName()
    {
        return 'ssp_transaction';
    }

    public function rules()
    {
        return [
		    ['id',   'safe'],
            ['name', 'safe'],
		    ['type', 'safe'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
            
        ];
    }

    public function behaviors()
    {
        
    }

    public function beforeSave($insert)
    {
       
    }

    public function afterDelete()
    {
        
    }
}