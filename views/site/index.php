<?php

use app\modules\user\models\User;

use app\modules\transaction_type\models\SspTransactionType;


/* @var $this yii\web\View */

$this->title = 'Семейный бюджет';

 
?>
<div class="site-index"> 
 
 
     <h2><?=$this->title?></h2>
        
     <div> Редактируемая таблица  -  возможность добавлять/удалять транзакции </div>  
     
     <br> <br>
         
   
    <table class="table-fill">
        <thead>
            <tr>
                <th style="width:400px;"  class="text-left">Категория</th>
                <th  style="width:300px;" class="text-left">Тип</th>
                <th style="width:500px;"  class="text-left">Сумма
                    <i  class="fa fa-plus"></i>  
                </th> 
            </tr> 
        </thead>  
        <tbody class="table-hover"> 
            <?php
            
            
            foreach ($list as $item) {
                echo \Yii::$app->view->renderFile('@app/views/site/editable_table/text_line.php',
                   [ 
                    "category" => $item["category"]["name"],
                    "type" => $item["category"]["type"]["name"],
                    "type_value" => $item["category"]["type"]["type"],  
                    "sum" => $item["sum"],
                    "id" => $item["id"], 
                  ]
                );
            }   
            
             echo \Yii::$app->view->renderFile('@app/views/site/editable_table/result_line.php',
                   [ 
                     "plus" =>   $result["plus"]["sum"],
                     "minus" =>  $result["minus"]["sum"], 
                     "sum" =>    $result["plus"]["sum"] -  $result["minus"]["sum"],
                  ]
                );
             
            ?> 
            
        </tbody>
    </table>
 
</div>
