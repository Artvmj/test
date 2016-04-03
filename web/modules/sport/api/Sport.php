<?php
namespace app\modules\sport\api;

use Yii;
use yii\easyii\components\API;
use yii\easyii\helpers\Data;
use yii\helpers\Url;
use app\modules\sport\models\Sport as SportModel;
use yii\helpers\Html;
use app\models\Sporthasdecor;

/**
 * Text module API
 * @package app\modules\sport\api
 *
 * @method static get(mixed $id_slug) Get text block by id or slug
 */
class Sport extends API
{
    private $_texts = [];

    public function init()
    {
        parent::init();

        $this->_texts = Data::cache(SportModel::CACHE_KEY, 3600, function(){
            return SportModel::find()->asArray()->all();
        });
    }
 
     public function api_getdecor($id)
    {   
           return SportModel::find()->where(['text_id' => $id])->with("decors");
    }
     
    
    public function api_get($id_slug)
    {
        if(($text = $this->findSport($id_slug)) === null){
            return $this->notFound($id_slug);
        }
        return LIVE_EDIT ? API::liveEdit($text['sport'], Url::to(['/admin/text/a/edit/', 'id' => $text['text_id']])) : $text['sport'];
    }

    private function findSport($id_slug)
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

        if(!Yii::$app->user->isGuest && preg_match(SportModel::$SLUG_PATTERN, $id_slug)){
            $text = Html::a(Yii::t('easyii/sport/api', 'Create text'), ['/admin/text/a/create', 'slug' => $id_slug], ['target' => '_blank']);
        }

        return $text;
    }
}