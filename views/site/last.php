<?php

use app\modules\user\models\User;
use app\modules\transaction_type\api\Type;

use app\modules\miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;


/* @var $this yii\web\View */

$this->title = 'Транзакции за последний час c интервалом 2 минуты'; 


?>
 <h2><?=$this->title?></h2> 
<?

 if(!isset($chart["x"]) || !isset($chart["y"]))
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
            'text' => "недавние транзакции",
        ],
        
         'credits' => [
            'enabled' => false,
        ],
        
        'xAxis' => [
            'categories' => $chart["x"] ,
        ],
         
         'chart' => [
            'type' => 'column',
        ],
        
        
        'series' => [
            [
                'name' => 'Количество транзакций', 
                'data' => $chart["y"],
            ],
             
            
        ],
    ]
]);   
   }

?> 

	 