<?php

namespace app\modules\transaction_category\models;

use Yii;

use  app\modules\transaction_type\models\SspTransactionType;
use  app\modules\transaction\models\SspTransaction;



/**
 * This is the model class for table "ssp_transaction_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 *
 * @property SspTransaction[] $sspTransactions
 * @property SspTransactionType $type
 */
class SspTransactionCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ssp_transaction_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type_id'], 'required'],
            [['type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SspTransactionType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'type_id' => 'Тип транзакции',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSspTransactions()
    {
        return $this->hasMany(SspTransaction::className(), ['category_id' => 'id']);
    }

     /**
     * Получить тип транзакции
      * 
      * @return \yii\db\ActiveQuery
     */ 
    public function getType()
    {
        return $this->hasOne(SspTransactionType::className(), ['id' => 'type_id']);
    }
    
    
    
    
     /**
     * Перед удалением категории удалить все транзакции категории
     */ 
    
       public function beforeDelete()
    {
        

        foreach ($this->getSspTransactions()->all() as $transaction) {
               $transaction->delete();
        }
        
         return parent::beforeDelete();
        
        
    }
    
    
    
    
}
