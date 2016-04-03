<?php
namespace app\modules\sizes\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\models\Photo;
use app\modules\sizes\models\Sizes as SizesModel;
use yii\helpers\Url;

class SizesObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $image;
    public $views;
    public $time;

    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getShort(){
        return LIVE_EDIT ? API::liveEdit($this->model->short, $this->editLink) : $this->model->short;
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

            foreach(Photo::find()->where(['class' => SizesModel::className(), 'item_id' => $this->id])->sort()->all() as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function  getEditLink(){
        return Url::to(['/admin/sizes/a/edit/', 'id' => $this->id]);
    }
}