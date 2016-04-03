<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\transaction\models\SspTransaction */

$this->title = 'Редактировать транзакцию: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Транзакции', 'url' => ['index']]; 
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="ssp-transaction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
