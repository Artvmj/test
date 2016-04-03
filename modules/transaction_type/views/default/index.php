<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\transaction_type\models\SspTransactionTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы транзакций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssp-transaction-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать тип транзакций', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
         
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 
            
            'id',
            'name', 
             [
            "label"=>"Тип операции",
            'attribute' => 'type', 
            'format'=>'raw',    
            'value' => function($model, $key, $index)
            {   
               
                    $value = ""; 
                    if($model->type)
                    $value =  $model->type=="plus"?"приходная":"расходная";  
                    return  $value;
               
            },
            ],
             
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
