<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\transaction\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Транзакции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssp-transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
   

    <p>
        <?= Html::a('Создать транзакцию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date_create',
            'date_update',
            
            
             "sum",
		 [
              'attribute' => 'category',
              'label' => 'Категория',
                            
              'value' => 'category.name'
                    ], 
			
			 [
               'attribute' => 'type',
                    'label' => 'Тип',         
                    'value' =>  'type.name'
             ], 
			
			
			[
              'attribute' => 'user',
                   'label' => 'Пользователь',          
                   'value' => 'user.username'
            ], 
			
			 

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
