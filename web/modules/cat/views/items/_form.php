<?php
use yii\easyii\helpers\Image;
use yii\easyii\widgets\DateTimePicker;
use yii\easyii\widgets\TagsInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;

  use app\modules\decor\api\Decor;
  use app\modules\sizes\api\Sizes;
  
  use app\modules\color\models\Color;
use yii\helpers\ArrayHelper;
  

$module = $this->context->module->id;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'slug') ?>

 
    <?php if($model->image) : ?>
        <img src="<?= Image::thumb($model->image, 240) ?>">
        <a href="<?= Url::to(['/admin/'.$module.'/items/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" 
           title="<?= Yii::t('easyii', 'Clear image')?>"><?= Yii::t('easyii', 'Clear image')?></a>  
          <br><br> 
         
     
          
          
 <?php if(!isset($_SESSION["watermark_color"])) $_SESSION["watermark_color"]="black";    ?>        
          
 <label class="watermark_check">
    черный
    <input  <?php if($_SESSION["watermark_color"]=="black") {   ?>checked<?php } ?>   type="radio" name="watermark_color"    value="black"> 
</label> 
<label class="watermark_check">
     белый
     <input   <?php if($_SESSION["watermark_color"]=="white") {   ?>checked<?php } ?>    type="radio"   name="watermark_color"    value="white">
</label>
          
     
          
        
          <br><br> 
          
          
    <?php endif; ?> 
         
         
  <?=$form->field($model, 'image')->fileInput()?>
        
 <?php   if(isset($category)   &&  !count($category->decor) &&   !count($category->sizes)  )   { 
       
       if($model->attr)
         $res =    json_decode($model->attr, true);
            
     ?>
        <h1>Аксессуары</h1>  
     
        <div style="height: 300px; overflow: auto">
        <table> 
          <?php     foreach (Decor::all() as $value) {
             
            ?><tr>
                 <td width="200px"><?=$value["name"]?></td>
                  <td width="50px">
                     <input  value="<?=isset($res["DECOR"][$value["text_id"]])?$res["DECOR"][$value["text_id"]]:0?>"  style="width:40px;
                             border:1px solid <?php echo (isset($res["DECOR"][$value["text_id"]])?"#00cd98":"gray")?>;  line-height: 1; font-size: 12px;" name="dec[<?=$value["text_id"]?>]" >  
                  </td> 
                 <td width="100px"><?=$value["price"]?> р</td> 
                 
               </tr>  
               <?php 
         }  
         ?></table>
            
           </div>    
           
         <h1>Мерки</h1> 
         
          <div style="height: 300px; overflow: auto">
           <table> 
          <?php     foreach (Sizes::all() as $value) {
             
            ?><tr>
                 <td width="200px"><?=$value["title"]?></td> 
                 <td width="50px">
                     <input  value="<?=isset($res["DECOR"][$value["sizes_id"]])?$res["DECOR"][$value["sizes_id"]]:0?>" style="width:40px; 
                             border:1px solid <?php echo (isset($res["DECOR"][$value["sizes_id"]])?"#00cd98":"gray")?>;  line-height: 1; font-size: 12px;"  
                             width="30px" name="siz[<?=$value["sizes_id"]?>]" >  
                 </td> 
               </tr>  
                <?php 
         }  
         ?></table>
        </div>  
         <br> <br> <br>
        
   <?php  } ?>
         
    <?= $form->field($model, 'price') ?>  
    <?=$form->field($model, 'color_list')->dropDownList(ArrayHelper::map(Color::find()->all(), 'id', 'title'),     ['multiple' => true])->label('Варианты цвета') ?> 
    <?= SeoForm::widget(['model' => $model]) ?>
 

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>