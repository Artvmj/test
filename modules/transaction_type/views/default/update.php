<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\transaction_type\models\SspTransactionType */

$this->title = 'Редактировать тип транзакции: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы транзакций', 'url' => ['index']];
 
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="ssp-transaction-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
