<?php
namespace app\modules\color\api;

use Yii;
use app\modules\color\models\Color as ColorModel;
use yii\helpers\Html;

/**
 * Color module API
 * @package app\modules\color\api
 *
 * @method static ColorObject get(mixed $id_slug) Get color object by id or slug
 */

class Color extends \yii\easyii\components\API
{
    private $_colors = [];

    public function api_get($id_slug)
    {
        if(!isset($this->_colors[$id_slug])) {
            $this->_colors[$id_slug] = $this->findColor($id_slug);
        }
        return $this->_colors[$id_slug];
    }

    

    private function notFound($id_slug)
    {
        $color = new ColorModel([
            'slug' => $id_slug
        ]);
        return new ColorObject($color);
    }
}