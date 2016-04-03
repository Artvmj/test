<?php
namespace app\modules\color;

use Yii;

class ColorModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Colors',
            'ru' => 'Цвета',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}