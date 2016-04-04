<?php

namespace app\modules\transaction_type\api;

use app\modules\transaction_type\models\SspTransactionType;

class Type {

    /**
     *  Возвращает массив всех типов транзакций
     * 
     * @return Array
     */
    public static function GetList() {
        $result = SspTransactionType::find()->asArray()->all();
        return $result;
    }

}
