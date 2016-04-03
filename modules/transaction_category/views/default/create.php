<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\transaction_category\models\SspTransactionCategory */

$this->title = 'Создать категорию транзакций';
$this->params['breadcrumbs'][] = ['label' => 'Категории транзакций', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssp-transaction-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
