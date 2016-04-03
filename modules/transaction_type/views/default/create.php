<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\transaction_type\models\SspTransactionType */

$this->title = 'Создать тип транзакции';
$this->params['breadcrumbs'][] = ['label' => 'Тип транзакции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssp-transaction-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
