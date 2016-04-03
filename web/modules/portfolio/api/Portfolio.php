<?php

namespace app\modules\portfolio\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\models\Tag;
use app\modules\portfolio\models\Category;
use app\modules\portfolio\models\Item;
use yii\easyii\widgets\Fancybox;

use app\widgets\Cpagerportfolio;



use yii\widgets\LinkPager;

class Portfolio extends \yii\easyii\components\API {

    private $_cats;
    private $_items;
    private $_adp;
    private $_item = [];
    private $_last;

    public function api_getlist($id_slug = false, $sort=false) {
 
      
        $opt = array('pagination' => ['pageSize' => 8]);
        
        if ($id_slug) {
              
             $category = $this->findCategory($id_slug); 
             $GLOBALS["CATEGORY_CODE"] = $category->slug;
             
            $opt["category_id"] =  ["category_id"=>$category->id];
           
        }
         
        
         
        $items = array();
       
        $items = $this->api_items($opt);

     

        return $items;
    }

    
    
    
    public function api_tree() {
        return Category::tree();
    }

 
    
    public function api_items($options = []) {
         
        
        if (!$this->_items) {
            $this->_items = [];

            $with = ['seo', 'category'];
              
           if(isset($options["category_id"]))
             $query = Item::find()->with($with)->where(['category_id' => $options["category_id"]])->status(Item::STATUS_ON);
            else
             $query = Item::find()->with($with)->status(Item::STATUS_ON);
 
                
            
            

            if (!empty($options['orderBy'])) {
                $query->orderBy($options['orderBy']);
            } else {
                $query->sortDate();
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach ($this->_adp->models as $model) {
                 
                $this->_items[] = new ArticleObject($model);
            }
        }
        return $this->_items;
    }  

    public function api_last($limit = 1, $where = null) {
        if ($limit === 1 && $this->_last) {
            return $this->_last;
        }

        $result = [];

        $with = ['seo'];
        if (Yii::$app->getModule('admin')->activeModules['cat']->settings['enableTags']) {
            $with[] = 'tags';
        }
        $query = Item::find()->with($with)->status(Item::STATUS_ON)->sortDate()->limit($limit);
        if ($where) {
            $query->andFilterWhere($where);
        }

        foreach ($query->all() as $item) {
            $result[] = new CatalogObject($item);
        }

        if ($limit > 1) {
            return $result;
        } else {
            $this->_last = count($result) ? $result[0] : null;
            return $this->_last;
        }
    }

    public function api_cats() {
        return Category::cats();
    }

    public function api_get($id_slug) {
        if (!isset($this->_item[$id_slug])) {
            $this->_item[$id_slug] = $this->findItem($id_slug);
        }
        return $this->_item[$id_slug];
    }

    public function api_plugin($options = []) {
        Fancybox::widget([
            'selector' => '.easyii-box',
            'options' => $options
        ]);
    }

    public function api_pagination() {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages() {
        
        
        return $this->_adp ? Cpagerportfolio::widget(['pagination' => $this->_adp->pagination]) : '';
        
        
    }

    private function findCategory($id_slug) {
        $category = Category::find()->where(['or', 'category_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(Item::STATUS_ON)->one();

        return $category ? new CategoryObject($category) : null;
    }

    private function findItem($id_slug) {
        $catalog = Item::find()->where(['or', 'item_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(Item::STATUS_ON)->one();
        if ($catalog) {
            $catalog->updateCounters(['views' => 1]);
            return new ArticleObject($catalog);
        } else {
            return null;
        }
    }

}
