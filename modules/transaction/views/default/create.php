<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\transaction\models\SspTransaction */

$this->title = 'Создать транзакцию';
$this->params['breadcrumbs'][] = ['label' => 'Транзакции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssp-transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
