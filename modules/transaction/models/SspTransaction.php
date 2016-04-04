<?php

namespace app\modules\transaction\models;

use app\modules\user\models\User;
use app\modules\transaction_category\models\SspTransactionCategory;
use app\modules\transaction_type\models\SspTransactionType;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "ssp_transaction".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 * @property integer $category_id
 * @property integer $user_id
 *
 * @property User $user
 * @property SspTransactionType $type
 * @property SspTransactionCategory $category
 */
class SspTransaction extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ssp_transaction';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [

            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sum', 'category_id', 'user_id'], 'required'],
            [['sum'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SspTransactionCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'sum' => 'Сумма',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'category_id' => 'Категория',
            'user_id' => 'Пользователь',
        ];
    }

    /**
     * Получить автора транзакции
      * 
      * @return \yii\db\ActiveQuery
     */ 
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Получить категорию транзакции
      * 
      * @return \yii\db\ActiveQuery
     */ 
    public function getCategory() {
        return $this->hasOne(SspTransactionCategory::className(), ['id' => 'category_id']);
    }

     /**
     * Получить тип транзакции
      * 
      * @return \yii\db\ActiveQuery
     */ 
    public function getType() {
        return $this->hasOne(SspTransactionType::className(), ['id' => 'type_id'])->via("category");
    }

}
