<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm; 
    use app\modules\decor\api\Decor;
    use app\modules\sport\api\Sport;  
    
    // use app\modules\decor\models\Text; 
     //$all =         $decor->api_get();
     use yii\helpers\ArrayHelper;
      
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['class' => 'model-form']
]); ?>
 
     <?= $form->field($model, 'name')   ?>
     <?= $form->field($model, 'slug')   ?> 
<table> 
        <tr> 
              <td style="width: 200px;">Аксессуар</td> 
              <td style="width: 50px;">
                    Количество от
              </td> 
              <td style="width: 50px;">
                     Количество до 
              </td> 
         </tr> 
 <?php   
 
   foreach (Sport::getdecor($model->text_id)->asArray()->all() as $m) {       //echo "<pre>";  print_r($value);   echo "</pre>";
       foreach ($m["values"] as $k=>$val) {  
       ?><tr> 
              <td style="width: 200px;"> 
                       <?=$m["decors"][$k]["name"]?></td> 
              
              <td style="width: 50px;">
                    <input  value="<?=$val["from"]?>"  name="from[<?=$val["id"]?>]"    type="text"/>
              </td> 
                <td style="width: 50px;">
                     <input  value="<?=$val["to"]?>"  name="to[<?=$val["id"]?>]" type="text"/>  
               </td> 
         </tr> 
             <?php  
       }  
  } 
?>

</table>
 
<?= $form->field($model, 'decor_list')->dropDownList(ArrayHelper::map(Decor::all(), 'text_id', 'name'),    ['multiple' => true]) ?>
  
<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>