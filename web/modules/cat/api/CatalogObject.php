<?php
namespace app\modules\cat\api;

use Yii;
use yii\easyii\components\API;
use app\models\Photo;
use app\modules\cat\models\Item;
use yii\helpers\Url;

class CatalogObject extends \yii\easyii\components\ApiObject
{
    /** @var  string */
    public $slug;

    public $image;

    public $views;
    public $price;
    public $attr;

    public $time;

    /** @var  int */
    public $category_id;

    private $_photos;
    
     public function getAttr(){
         
         
             $attr = [];
         if(isset($this->model->attr))
               $attr =    json_decode($this->model->attr, true);
        
         
        return $attr;
    }
    
    

    public function getTitle(){
        return   $this->model->title;
    }

    public function getShort(){
        return   $this->model->short;
    }

    public function getText(){
        return   $this->model->text;
    }

    public function getCat(){
        return Catalog::cats()[$this->category_id];
    }

    public function getTags(){
        return $this->model->tagsArray;
    }

    public function getDate(){
        return Yii::$app->formatter->asDate($this->time);
    }

    public function getPhotos()
    {
        if(!$this->_photos){
            $this->_photos = []; 
            
              foreach(Photo::find()->where(['class' => Item::className(), 'item_id' => $this->id])->sort()->all() as $model){
                    $this->_photos[] = new PhotoObject($model);
              }
             
        }
        
        return $this->_photos;
    } 
    
    public function getEditLink(){
        return Url::to(['/admin/catalog/items/edit/', 'id' => $this->id]);
    }
}