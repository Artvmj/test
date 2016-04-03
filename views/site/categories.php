<?php

use app\modules\user\models\User;
use app\modules\transaction_type\api\Type;

use app\modules\miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;


/* @var $this yii\web\View */

$this->title = 'Отчет по категориям транзакций'; 

?>
 <h2><?=$this->title?></h2> 

<?
 
 if(!isset($chart["x"]))
 {
     ?><h2>Недостаточно данных для построения графика</h2><?
 } else


 {


echo Highcharts::widget([
    'scripts' => [
        'modules/exporting',
        'themes/grid-light',
    ],
    'options' => [
        'title' => [
            'text' => $this->title,
        ],
        
         'credits' => [
            'enabled' => false,
        ],
        
        'xAxis' => [
            'categories' =>  $chart["x"] ,
        ],
         
         'chart' => [
            'type' => 'column',
        ],
        
        
        'series' => [
            [
                'name' => 'Доход', 
                'data' => $chart["y"] ,
            ],
             
            
        ],
    ]
]); 

 }



?> 

	 


 