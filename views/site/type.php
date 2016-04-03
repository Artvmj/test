<?php

use app\modules\user\models\User;
use app\modules\transaction_type\api\Type;

use app\modules\miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;


/* @var $this yii\web\View */

$this->title = 'Отчет по типам операций'; 

 ?>
    <h2><?=$this->title?></h2> 
 <?php
 
 
 if(!count($chart["sum"]) || !count($chart["count"]))
 {
     ?><h2>Недостаточно данных для построения графика</h2><?
 }
   else
   {

echo Highcharts::widget([
    'scripts' => [
        'modules/exporting',
        'themes/grid-light',
    ],
    'options' => [
        'title' => [
            'text' => 'Сумма',
        ], 
        'series' => [ 
            [
                'type' => 'pie',
                'name' => 'Сумма',
                'data' => $chart["sum"], 
                'size' => 300,
                'showInLegend' => false,
                'dataLabels' => [
                    'enabled' => false,
                ],
            ],
        ],
    ]
]);
   

echo Highcharts::widget([
    'scripts' => [
        'modules/exporting',
        'themes/grid-light',
    ],
    'options' => [
        'title' => [
            'text' => 'Количество',
        ], 
        'series' => [ 
            [
                'type' => 'pie',
                'name' => 'Транзакций',
                'data' => $chart["count"],  
                'size' => 300,
                'showInLegend' => false,
                'dataLabels' => [
                    'enabled' => false,
                ],
            ],
        ],
    ]
]);


   }







?> 

	 