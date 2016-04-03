<?php
namespace app\modules\cat\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\Taggable;
use yii\easyii\models\Photo;
use yii\helpers\StringHelper;
use app\modules\color\models\Color;


class Item extends \yii\easyii\components\ActiveRecord
{
    
    const CACHE_KEY = 'app_item';
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName()
    {
        return 'app_cat_items';
    }

    public function rules()
    {
        return [ 
            [['title', 'short', 'text'], 'trim'],
            [[ 'title', 'price', 'slug'], 'required'],
            
            [['color_list'], 'safe'], 
            
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            [['category_id', 'views', 'time', 'status','price'], 'integer'],
            ['time', 'default', 'value' => time()],
          //  ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ON], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' =>  "Наименование", 
            'image' => "Изображение",
             'price' => "Цена",
            'time' => "Дата",
            'slug' => "Символьный код", 
        ];
    }

    public function behaviors()
    {
        return [
            
            
             
            [
                'class' => \app\behaviors\ManyHasManyBehavior::className(),
                'relations' => [ 
                    'color' => 'color_list',
                ]
                
            ] 
            ,
             
              [
                'class' => \app\behaviors\Water::className(),
                
              ]
            ,
            
            
            
            
            'seoBehavior' => SeoBehavior::className(),
          //'taggabble' => Taggable::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    
    public function getColor() {
               return $this->hasMany(Color::className(), ['id' => 'color_id'])->viaTable('app_item_has_color', ['item_id' => 'item_id']);
    } 
    
    
    
       public static function  allcolor($id)
    {   
           return self::findOne($id)->color;
    }
    
    
    
    
    
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'item_id'])->where(['class' => self::className()])->sort();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $settings = Yii::$app->getModule('admin')->activeModules['cat']->settings;
          //  $this->short = StringHelper::truncate($settings['enableShort'] ? $this->short : strip_tags($this->text), $settings['shortMaxLength']);

            if(!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->image){
            @unlink(Yii::getAlias('@webroot').$this->image);
        }

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}