<?php
use yii\easyii\helpers\Image;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;
 
  use app\modules\decor\api\Decor;
  use app\modules\sizes\api\Sizes;
 
use yii\helpers\ArrayHelper;


$class = $this->context->categoryClass;
$settings = $this->context->module->settings;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?=$form->field($model, 'title') ?> 
<?=$form->field($model, 'slug') ?>

 
<?php if(!empty($parent)) : ?>
    <div class="form-group field-category-title required">
        <label for="category-parent" class="control-label"><?= Yii::t('easyii', 'Parent category') ?></label>
        <select class="form-control" id="category-parent" name="parent">
            <option value="" class="smooth"><?= Yii::t('easyii', 'No') ?></option>
            <?php foreach($class::find()->sort()->asArray()->all() as $node) : ?>
                <option
                    value="<?= $node['category_id'] ?>"
                    <?php if($parent == $node['category_id']) echo 'SELECTED' ?>
                    style="padding-left: <?= $node['depth']*20 ?>px;"
                ><?= $node['title'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>

 

        
  <?= $form->field($model, 'decor_list')->dropDownList(ArrayHelper::map(Decor::all(), 'text_id', 'name'),     ['multiple' => true])->label('Аксессуары в категории') ?>
        
  <?= $form->field($model, 'size_list')->dropDownList(ArrayHelper::map(Sizes::all(), 'sizes_id', 'title'),    ['multiple' => true])->label('Размеры  в категории') ?>      
        
 
 
    <?= SeoForm::widget(['model' => $model]) ?>
 

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>