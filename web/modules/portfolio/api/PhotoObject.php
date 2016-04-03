<?php
namespace app\modules\portfolio\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \yii\easyii\components\ApiObject
{
    public $image;
    public $description;

  public function box($width, $height){
        $img = Html::img($this->thumb($width, $height), ['class' => 'img-responsive']);
       
        return $img;
    }

    public function getEditLink(){
        return Url::to(['/admin/article/items/photos', 'id' => $this->model->item_id]).'#photo-'.$this->id;
    }
}