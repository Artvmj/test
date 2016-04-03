<?php
namespace app\modules\color\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class ColorObject extends \yii\easyii\components\ApiObject
{
    public $slug;

    public function getTitle(){
        if($this->model->isNewRecord){
            return $this->createLink;
        } else {
            return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
        }
    }

   

   

    public function getCreateLink(){
        return Html::a(Yii::t('easyii/color/api', 'Create color'), ['/admin/color/a/create', 'hex' => $this->hex], ['target' => '_blank']);
    }
}