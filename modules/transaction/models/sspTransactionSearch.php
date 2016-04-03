<?php

namespace app\modules\transaction\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transaction\models\sspTransaction;

/**
 * sspTransactionSearch represents the model behind the search form about `app\modules\transaction\models\sspTransaction`.
 */
class sspTransactionSearch extends sspTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'  ], 'integer'],
            [['sum'], 'integer'],
			[['category', 'user'  , 'type' ], 'safe'], 
        ];
    }
	
	
	 public  $type;
	

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = sspTransaction::find();
		
		
          $query->joinWith(['type', 'user']);
		

        // add conditions that should always apply here
		 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		
		
	 
    
    $dataProvider->sort->attributes['category'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "ssp_"
        'asc' => ['ssp_transaction_category.name' => SORT_ASC],
        'desc' => ['ssp_transaction_category.name' => SORT_DESC],
    ]; 
    
     
	 $dataProvider->sort->attributes['type'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "ssp_"
        'asc' => ['ssp_transaction_type.name' => SORT_ASC],
        'desc' => ['ssp_transaction_type.name' => SORT_DESC],
    ]; 	
		 
    $dataProvider->sort->attributes['user'] = [
        'asc' =>   ['user.username' => SORT_ASC],
        'desc' =>  ['user.username' => SORT_DESC],
    ];
		 

     $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id, 
        ]); 
		
		  $query->andFilterWhere(['like', 'sum', $this->sum]);
		 
 
		
		
		
		

        

        return $dataProvider;
    }
}
