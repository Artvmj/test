<?php
use yii\easyii\helpers\Image;
use yii\easyii\widgets\DateTimePicker;
use yii\easyii\widgets\TagsInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;

$module = $this->context->module->id;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>


<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'slug') ?>

<?php if($this->context->module->settings['articleThumb']) : ?>
    <?php if($model->image) : ?>
        <img src="<?= Image::thumb($model->image, 240) ?>">
        <a href="<?= Url::to(['/admin/'.$module.'/items/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii', 'Clear image')?>"><?= Yii::t('easyii', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>
<?php endif; ?>
        
        
<?php if(!isset($_SESSION["watermark_color"])) $_SESSION["watermark_color"]="black";    ?>        
          
 <label class="watermark_check">
    черный
    <input  <?php if($_SESSION["watermark_color"]=="black") {   ?>checked<?php } ?>   type="radio" name="watermark_color"    value="black"> 
</label> 
<label class="watermark_check">
     белый
     <input   <?php if($_SESSION["watermark_color"]=="white") {   ?>checked<?php } ?>    type="radio"   name="watermark_color"    value="white">
</label>
        
        
        
        

 

<?= $form->field($model, 'text')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'portfolio'], true),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'portfolio'], true),
        'plugins' => ['fullscreen']
    ]
]) ?>

 

 

 
    
    <?= SeoForm::widget(['model' => $model]) ?>
 

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>