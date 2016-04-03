<?php

namespace app\modules\transaction_category\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transaction_category\models\SspTransactionCategory;

/**
 * SspTransactionCategorySearch represents the model behind the search form about `app\modules\transaction_category\models\SspTransactionCategory`.
 */
class SspTransactionCategorySearch extends SspTransactionCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

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
        $query = SspTransactionCategory::find();

        // add conditions that should always apply here
 
		 $query->joinWith(['type']);
		
         $dataProvider = new ActiveDataProvider(['query' => $query]);
           
        $dataProvider->sort->attributes['type'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "ssp_"
        'asc' => ['ssp_transaction_type.name' => SORT_ASC],
        'desc' => ['ssp_transaction_type.name' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
