<?php
namespace app\modules\portfolio\models;

use Yii;

class Category extends \yii\easyii\components\CategoryModel
{
    public static function tableName()
    {
        return 'app_portfolio_categories';
    }

    
         public function rules()
    {
       
        
        
         return [
            ['title', 'required'],
             ['slug', 'required'],
            ['title', 'trim'],
            
             
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON]
        ];
         
    }
    
    
    
    
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->sortDate();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->getItems()->all() as $item) {
            $item->delete();
        }
    }
}