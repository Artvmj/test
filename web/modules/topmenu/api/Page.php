<?php
namespace app\modules\topmenu\api;

use Yii;
use app\modules\topmenu\models\Page as PageModel;
use yii\helpers\Html;

/**
 * Page module API
 * @package app\modules\topmenu\api
 *
 * @method static PageObject get(mixed $id_slug) Get page object by id or slug
 */

class Page extends \yii\easyii\components\API
{
    private $_pages = [];

    public function api_get($id_slug)
    {
        if(!isset($this->_pages[$id_slug])) {
            $this->_pages[$id_slug] = $this->findPage($id_slug);
        }
        return $this->_pages[$id_slug];
    }
    
    
     public  static  function getList()
    {
        $page = PageModel::find()->orderBy(["time" => SORT_DESC])->all();
         
        return $page;
    }
    
     
    

    private function findPage($id_slug)
    {
        $page = PageModel::find()->where(['or', 'page_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $page ? new PageObject($page) : $this->notFound($id_slug);
    }

    private function notFound($id_slug)
    {
        $page = new PageModel([
            'slug' => $id_slug
        ]);
        return new PageObject($page);
    }
}