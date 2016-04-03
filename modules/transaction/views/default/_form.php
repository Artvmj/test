<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use  app\modules\user\models\User; 
use  app\modules\transaction_category\models\SspTransactionCategory;
 

use  app\modules\transaction_type\models\SspTransactionType;



/* @var $this yii\web\View */
/* @var $model app\modules\transaction\models\SspTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ssp-transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sum')->textInput() ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(SspTransactionCategory::find()->all(),'id','name') , ['prompt' => '']) ?> 
    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::find()->all(),'id','username') , ['prompt' => '']) ?> 
    
    
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('К списку', ['index'], ['class' => 'btn btn-success']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>
