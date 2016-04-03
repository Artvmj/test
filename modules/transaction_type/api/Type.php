<?php

namespace  app\modules\transaction_type\api;
use        app\modules\transaction_type\models\SspTransactionType;

class Type   {
 
    public static function GetList() { 
		$result = SspTransactionType::find()->asArray()->all(); 
        return $result;
    }
 
}
