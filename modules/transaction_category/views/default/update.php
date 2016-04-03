<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\transaction_category\models\SspTransactionCategory */

$this->title = 'Редактировать категорию транзакций: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории транзакций', 'url' => ['index']]; 
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="ssp-transaction-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
