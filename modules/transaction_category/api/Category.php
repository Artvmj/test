<?php

namespace  app\modules\transaction_category\api;
use        app\modules\transaction_category\models\SspTransactionCategory;

class Category   {
 
    public static function GetList() { 
		$result = SspTransactionCategory::find()->with("type")->asArray()->all(); 
        return $result;
    }
 
}
