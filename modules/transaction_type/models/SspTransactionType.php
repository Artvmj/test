<?php

namespace app\modules\transaction_type\models;
use  app\modules\transaction_category\models\SspTransactionCategory;
use  app\modules\transaction\models\SspTransaction;
use Yii;

/**
 * This is the model class for table "ssp_transaction_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 *
 * @property SspTransactionCategory[] $sspTransactionCategories
 */
class SspTransactionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ssp_transaction_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type' ], 'required'],
            [['type'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'type' => 'Тип операции',
           
        ];
    }

   /**
     * Получить категории данного типа транзакции
      * 
      * @return \yii\db\ActiveQuery
     */ 
    public function getSspTransactionCategories()
    {
        return $this->hasMany(SspTransactionCategory::className(), ['type_id' => 'id']);
    }
    
   
      /**
     * Перед удалением типа транзакции удалить все категории и транзакции, принадлежащие к типу
      */ 
    
       public function beforeDelete()
    {
       

        foreach ($this->getSspTransactionCategories()->all() as $category) {
            $category->delete();
        }
        
         return parent::beforeDelete();
        
        
    }
    
   
    
    
     
    
}
