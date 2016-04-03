<?php  use yii\helpers\Url;
use app\modules\cat\api\Catalog;

?>
           
<table>
    <tr>
        
        <td width="150px"> 
            <img src="<?=$catalog->thumb(100, 100) ?>"/>
        </td>
        
        <td> 
               <h1><?=$catalog->title?></h1> 
               <div class="view_price"><?= Catalog::price($catalog->price) ?>  <i class="fa fa-rouble"></i> </div> 
        </td>
        
    </tr> 
</table>
 

<table class="table"> 

                <tr> 
                    <td   colspan="4"> 
                        <h1>Аксессуары:</h1>
                    </td>  
                </tr> 


                <tr> 
                    <th style="width: 350px;"> 
                        Наименование
                    </th> 

                    <th style="width: 120px;">  
                        количество  
                    </th> 

                    <th style="width: 90px;">  
                        цена
                    </th> 

                    
                    <th style="width: 50px;">  
                        стоимость 
                    </th> 
                </tr> 

                <?php
              
                 $TOTAL = 0;
                foreach ($good->decors as $decor) {  //echo "<pre>";  print_r($value);   echo "</pre>";
                   
 
                        
                         $TOTAL+= $decor->count * $decor->price;
                        
                        ?><tr> 
                            <td> 
                                <?= $decor->name ?>
                            </td>  
                            <td>  
                                 <?=$decor->count?> 
                            </td>  
                            <td>  
                                <span><?=Catalog::price($decor->price) ?> <i   class="fa fa-rouble"></i> </span> 
                            </td> 
                           
                            <td>  
                                <?php echo Catalog::price($decor->count * $decor->price) ?> <i   class="fa fa-rouble"></i>
                            </td>  
                        </tr> 
                        <?php
                    
                }
                ?>       
                <tr  class="tr_border"> 
                    <td    class="total_price" colspan="3"> 
                          Итого:
                    </td>  
                    <td> 
                        <span><?=Catalog::price($TOTAL)?></span><i class="fa fa-rouble"></i>
                    </td> 

                </tr> 

                <tr> 
                    <td     class="total_price" colspan="3"> 
                          Стоимость модели 
                    </td>  

                    <td> 
                        <?= Catalog::price($good->item_price) ?>  </span><i class="fa fa-rouble"></i>
                    </td> 

                </tr> 

                <tr> 
                    
                    <td class="total_price" colspan="3"> 
                        Общая    Стоимость модели   
                    </td>  

                    <td> 
                        <?= Catalog::price($good->price) ?>  </span><i class="fa fa-rouble"></i>  </span> 
                    </td> 
 
                </tr>  
            </table> 
 
<table class="table"> 

                <tr> 
                    <td > 
                        <h1>Комментарий:</h1>
                    </td>  
                </tr> 

             <tr> 
                    <td > 
                        <?php echo $good->comment ?>
                    </td>  
                </tr> 
                
   </table>              
                
              

    <table class="table"> 

              <tr> 
                    <td> 
                        <h1>Прикрепленные изображения:</h1>
                    </td>  
                </tr> 
             <tr> 
                    <td> 
                        <?php 
                        
                               if($good->img)
                               {
                                   $arImg = explode("#",   $good->img); 
                                   $imageFolder2 =  "/uploads/order_images/";
            
                                   foreach ($arImg as $img) {
                                        
                                       ?><img src="<?=$imageFolder2.$img?>"/>   <?    
                                         
                                   }
                                   
                                   
                                   
                                   
                               }  
                                   
                                //  echo $good->img
                                           
                                
                                ?>
                    </td>  
            </tr> 
                
   </table>  












<table class="table"> 

                <tr> 
                    <td> 
                        <h1>Цвет:</h1>
                    </td>  
                </tr> 

                <tr> 
                    <td> 
                        <?php 
                        
                        
                                 echo  $good->color_name; 
                        
                        
                                ?>
                    </td>  
                </tr> 
                
   </table>  






                
                


           <table class="table"> 

                <tr> 
                    <td class="total_price" colspan="2"> 
                        <h1>Размеры:</h1>
                    </td>  
                </tr>  
                <tr> 
                    <th style="width: 350px;"> 
                        Наименование
                        </th>  

                        <th style="width: 80px;">  
                            Размер
                      </th>  
                   
                </tr> 

                <?php
                $TOTAL = 0;

                foreach ($good->sizes as $size) { 
                   
                        ?><tr> 
                            <td> 
                                <?=$size->name?>
                            </td>  
                            <td>  
                                <?=$size->count?>
                            </td> 
                        </tr> 
                        <?php
                    }
               
                ?>    
            </table> 
             
              
            <div style="clear:both;"></div>  
            
              
               
            
                    <a href="<?php  echo  Url::to(['/admin/orders/a/view', 'id' => $good->order_id]);  ?>">
                          <button class="btn btn-info" type="button">Закрыть</button>  
                     </a>
                     
             
                     <br><br><br>      <br><br><br> <br><br><br>   