<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\transaction_type\models\SspTransactionType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ssp-transaction-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?> 
    <?= $form->field($model, 'type')->dropDownList(   ["plus"=>"Приходная операция", "minus"=>"Расходная операция"], ['prompt' => ''] ) ?>
    
     

    <div class="form-group">
        <?= Html::submitButton('Применить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
