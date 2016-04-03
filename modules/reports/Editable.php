<?php

namespace app\modules\reports;

use app\modules\user\models\User;
use app\modules\transaction_category\models\SspTransactionCategory;
use app\modules\transaction_type\models\SspTransactionType;
use app\modules\transaction\models\SspTransaction;

class Editable { 
    /**
     * Возвращает список транзакций определенного пользователя
     *
     * @param int $user_id
     *
     * @return array
     */
    public static function getList($user_id) {

        $data = SspTransaction::find()
                ->where(['user_id' => $user_id])
                ->with(['category' => function($q) {  $q->with('type');  }], 'INNER JOIN') 
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all(); 
        return $data;
    }

}
