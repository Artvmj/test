<?php
namespace app\modules\sizes;

class SizesModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableThumb' => true,
        'enablePhotos' => true,
        'enableShort' => true,
        'shortMaxLength' => 256,
        'enableTags' => true
    ];

    public static $installConfig = [
        'title' => [
            'en' => '',
            'ru' => 'Размеры',
        ],
        'icon' => 'bullhorn',
        'order_num' => 70,
    ];
}