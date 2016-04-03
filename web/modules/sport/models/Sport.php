<?php

namespace app\modules\sport\models;


use Yii;
use yii\easyii\behaviors\CacheFlush;
use app\modules\decor\models\Decor;
use app\models\Sporthasdecor;


class Sport extends \yii\easyii\components\ActiveRecord {

    const CACHE_KEY = 'easyii_text';

    public static function tableName() {
        return 'app_sports';
    }

    public function rules() {
        return [
            ['text_id', 'number', 'integerOnly' => true],
            ['name', 'required'],
            [['decor_list'], 'safe'],
            ['name', 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'required'],
            ['slug', 'unique']
        ];
    }

  /*  public function getDecor() {
        return $this->hasMany(Sport::className(), ['text_id' => 'sport_id'])
                        ->viaTable('decor_has_sport', ['decor_id' => 'text_id']);
    } */
    
    public function getDecor()
   {
           return $this->hasMany(Decor::className(), ['text_id' => 'decor_id'])
             ->viaTable('app_sport_has_decor', ['sport_id' => 'text_id']);
   }
   
    
     
   public function getValues()
   {
          return $this->hasMany(Sporthasdecor::className(), ['sport_id' => 'text_id']);
   }
   
   
    public function getDecors() 
{
    return $this->hasMany(Decor::className(), ['text_id' => 'decor_id'])->via('values');
   
}
    
   /* 
     public function getDecorval($id)
    {   
           return self::find()->where(['text_id' => $id])->with("decors");
          
    } */
     
    //public function getDecorNum()
  // {
     //  return $this->hasMany(Decor::className(), ['text_id' => 'decor_id'])
      //    ->viaTable('sport_has_decor', ['sport_id' => 'text_id']);
  // }
   
   
   
    
   /*
    public function getTags()
   {
       return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
        ->viaTable('post_has_tag', ['post_id' => 'id']);
   } */
 

    public function attributeLabels() {
        return [
            'name' => "Наименование",
            'slug' => "Символьный код",
        ];
    }
 /*
    public function behaviors() {
        return [ 
               CacheFlush::className() 
        ];
    }   */
    
    
    public function behaviors() {
  
          return [ 
            [ 
            'class' => \app\behaviors\ManyHasManyBehavior::className(),
            'relations' => [ 
                'decor' => 'decor_list'                    
            ]
       ]
    ];
        
        
        
}
 
    
    
    
    

}
