<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use  app\modules\transaction_type\models\SspTransactionType;


/* @var $this yii\web\View */
/* @var $model app\modules\transaction_category\models\SspTransactionCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ssp-transaction-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'type_id')->dropDownList(ArrayHelper::map(SspTransactionType::find()->all(),'id','name') , ['prompt' => '']) ?>
	 

    <div class="form-group">
        <?= Html::submitButton( 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('К списку', ['index'], ['class' => 'btn btn-success']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>
