<?php

use app\modules\user\models\User;
use app\modules\transaction_type\api\Type;

use app\modules\miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;


/* @var $this yii\web\View */

$this->title = 'Отчет по типам транзакций'; 
 
?>  

<div class="site-index"> 
    
    
      <h2><?=$this->title?></h2>
        
      <div>  Таблица транзакций по категориям.  Если категория содержит несколько транзакций, их можно увидеть в раскрывающимся списке </div>  
     
       
       <?

 if(!isset( $list["limit"]) || !isset( $list["data"]))
 {
     ?><h2>Недостаточно данных для построения таблицы</h2><?
 }
   else
   { ?>
       
       
     <br> <br>
    
    
    
    
    
    <table class="table-fill">
        <thead>
            <tr> 
                <? foreach ($list["data"] as $type) { ?>
                    <th style="width:400px;"  class="text-left"><?= $type["name"] ?></th> 
                <? } ?>  
            </tr> 
        </thead>  
        <tbody class="table-hover">  
            <? for ($row = 0; $row < $list["limit"]; $row++) { ?>  
                <tr> 
                    <? foreach ($list["data"] as $column => $type) { ?>
                        <td> 
                            <?
                            if (isset($list["data"][$column]["sspTransactionCategories"][$row]["name"])) {  
                                if(isset($list["data"][$column]["sspTransactionCategories"][$row]))
                                {  
                                    $category = $list["data"][$column]["sspTransactionCategories"][$row];      //категория транзакций
                                   
                                ?> 
                                <div style="display: inline-block;" class="dropdown"> 
                                    <button data-toggle="dropdown" type="button" class="btn    
                                            dropdown-toggle catalog-filter" aria-expanded="true">
                                        <span><?=$category["name"];?>  <?=$category["sum"]?>  <i class="fa fa-rouble"></i>  </span>
                                        
                                      <? if(count($category["sspTransactions"])>1) { ?>    
                                              <i class="fa fa-caret-down"></i>
                                      <?  } ?>      
                                        
                                    </button> 
                                    
                                     <? if(count($category["sspTransactions"])>1) {   //показать раскрывающийся список если категория содержит несколько транзакций    ?>  
                                       <ul class="dropdown-menu catalog">  
                                        <? foreach ($category["sspTransactions"] as $item) { ?>
                                           <li id="<?= $item["id"] ?>" > <span style="padding-left: 15px;"><?= $item["sum"] ?> <i class="fa fa-rouble"></i> </span></li>  
                                        <? } ?>  
                                      </ul> 
                                     <?  } ?>  
                                </div> 
                                <? 
                                  } 
                            }
                            ?> 
                        </td> 
                    <? } ?>  

                </tr> 

                <?
            }
            ?> 
        </tbody> 
            <tr> 
                <? foreach ($list["data"] as  $key=>  $type) { ?>
                    <td style="background: lightblue;"><?=$type["sum"]?>  <i class="fa fa-rouble"></i></td> 
                <? } ?>  
            </tr> 
            
             <tr>   
                      <td  colspan="<?=count($list["data"])-1?>"  style="background: #dcdcdc;">Итоговый доход:</td> 
                      <td style="background: #dcdcdc;"><?=$list["total_sum"]?>  <i class="fa fa-rouble"></i></td>  
            </tr>  
            
    </table>
    
    
    
   <? } ?>
    
</div>
