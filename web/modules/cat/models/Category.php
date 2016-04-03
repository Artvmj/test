<?php
namespace app\modules\cat\models;

use app\modules\decor\models\Decor;
use app\modules\sizes\models\Sizes;

use app\models\Sporthasdecor;
use app\models\Sporthassize;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SeoBehavior;
use creocoder\nestedsets\NestedSetsBehavior;


class Category extends \yii\easyii\components\CategoryModel
{
    public static function tableName()
    {
        return 'app_cat_categories';
    }
     
      public function rules()
    {
       
        
        
         return [
            ['title', 'required'],
             ['slug', 'required'],
            ['title', 'trim'],
            [['decor_list'], 'safe'],
            [['size_list'], 'safe'],
             
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON]
        ];
         
    }
      
      public static function  alldecor($id)
    {   
           return self::find()->where(['category_id' => $id])->with("decors");
    }
        public static function  allsize($id)
    {   
           return self::find()->where(['category_id' => $id])->with("sizes");
    }
    
    public function getDecor() {
        return $this->hasMany(Decor::className(), ['text_id' => 'decor_id'])
                        ->viaTable('app_sport_has_decor', ['sport_id' => 'category_id']);
    }

    
    
    public function getValues() {
        return $this->hasMany(Sporthasdecor::className(), ['sport_id' => 'category_id']);
    }

     
    
  //размеры, привязанные к категории 
     public function getSizeValues() {
        return $this->hasMany(Sporthassize::className(), ['sport_id' => 'category_id']);
    }
    
       public function getSizes() {
        return $this->hasMany(Sizes::className(), ['sizes_id' => 'size_id'])->via('sizeValues');
    }
    
     public function getSize() {
        return $this->hasMany(Sizes::className(), ['sizes_id' => 'size_id'])
                        ->viaTable('app_sport_has_size', ['sport_id' => 'category_id']);
    } 
    //размеры, привязанные к категории
     
    
    public function getDecors() {
        return $this->hasMany(Decor::className(), ['text_id' => 'decor_id'])->via('values');
    }

    public function behaviors() {

        return [
            [
                'class' => \app\behaviors\ManyHasManyBehavior::className(),
                'relations' => [
                    'decor' => 'decor_list',
                    'size' => 'size_list',
                ]
            ]
            ,
            
            'cacheflush' => [
                'class' => CacheFlush::className(),
                'key' => [static::tableName().'_tree', static::tableName().'_flat']
            ],
            'seoBehavior' => SeoBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ]
            
            
            
            
            
            
            
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