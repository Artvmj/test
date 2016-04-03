<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\transaction_category\models\SspTransactionCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории транзакций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssp-transaction-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать категорию транзакций', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
			
			[
              'attribute' => 'type',
              'label' => 'тип транзакции', 
              'value' => 'type.name'
            ], 
			

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
