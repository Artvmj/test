<?php
namespace app\modules\decor\api;

use Yii;
use yii\easyii\components\API;
use app\models\Photo;
use app\modules\cat\models\Item;
use yii\helpers\Url;
 use app\modules\decor\api\Decor;

class DecorObject extends \yii\easyii\components\ApiObject
{
    /** @var  string */
    public $slug; 
    public $name;
 
     public function getName(){
        return  $this->model->name;
     }
 
}