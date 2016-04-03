<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['class' => 'model-form']
]); ?>
 
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'slug')->label('Значения по умолчанию, через запятую') ?>



<?= $form->field($model, 'price') ?>




 
<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>


