<?php
namespace app\modules\decor\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\helpers\Data;
use yii\helpers\Url;
use app\modules\decor\models\Decor as DecorModel;
use yii\helpers\Html;

/**
 * Text module API
 * @package app\modules\decor\api
 *
 * @method static get(mixed $id_slug) Get text block by id or slug
 */
class Decor extends API
{
    private $_texts = [];

    public function init()
    {
        parent::init();

        $this->_texts = Data::cache(DecorModel::CACHE_KEY, 3600, function(){
            return DecorModel::find()->asArray()->all();
        });
    }

    
    public function api_all()
    {
       return DecorModel::find()->asArray()->all();
    }
    
    
    
    public function api_getbyid($id)
    { 
            return DecorModel::findOne($id); 
    }
  
    
    
   
    
    public function api_get($id_slug)
    {
        if(($text = $this->findDecor($id_slug)) === null){
            return $this->notFound($id_slug);
        }
        return LIVE_EDIT ? API::liveEdit($text['decor'], Url::to(['/admin/text/a/edit/', 'id' => $text['text_id']])) : $text['decor'];
    }

    private function findDecor($id_slug)
    {
        foreach ($this->_texts as $item) {
            if($item['slug'] == $id_slug || $item['text_id'] == $id_slug){
                return $item;
            }
        }
        return null;
    }

    private function notFound($id_slug)
    {
        $text = '';

        if(!Yii::$app->user->isGuest && preg_match(DecorModel::$SLUG_PATTERN, $id_slug)){
            $text = Html::a(Yii::t('easyii/decor/api', 'Create text'), ['/admin/text/a/create', 'slug' => $id_slug], ['target' => '_blank']);
        }

        return $text;
    }
}