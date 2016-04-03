<?php
namespace app\modules\cat;

class CatModule extends \yii\easyii\components\Module
{
    public $settings_cat = [
        'categoryThumb' => true,
        'catalogThumb' => true,
        'enablePhotos' => true,

        'enableShort' => true,
        'shortMaxLength' => 255,
        'enableTags' => true,

        'itemsInFolder' => false,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Catalog',
            'ru' => 'Каталог',
        ],
        'icon' => 'pencil',
        'order_num' => 65,
    ];
}