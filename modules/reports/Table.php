<?php

namespace app\modules\reports;

use app\modules\user\models\User;
use app\modules\transaction_category\models\SspTransactionCategory;
use app\modules\transaction_type\models\SspTransactionType;
use app\modules\transaction\models\SspTransaction;

class Table {

    static $user_id;

    public static function getList($user_id) {

        self::$user_id = $user_id; 
        
        
        $arResult = array();
        $arResult["data"] = SspTransactionType::find()->joinWith(['sspTransactionCategories' => function($q) {
                $q->joinWith(['sspTransactions' => function($q) {
                $q->where(['user_id' => self::$user_id]);
            }], 'INNER JOIN');
            }], 'INNER JOIN')
                ->asArray()
                ->all(); 
            
            
            
        $sum = array();

        ///////посчитать сумму по категориям, и общую сумму по типам транзакций для общей таблицы

        foreach ($arResult["data"] as $key => $type) {
            $sum["limit"][$key] = count($type["sspTransactionCategories"]);
            foreach ($type["sspTransactionCategories"] as $cat_key => $category) {
                foreach ($category["sspTransactions"] as $transaction) {
                    $sum["cat"][$key][$cat_key][] = $transaction["sum"];
                    $sum["all"][$key][] = $transaction["sum"];
                }
            }
        }

        $total_sum = 0;
        
        
        foreach ($arResult["data"] as $key => &$type) { 
            
            $type["sum"] = array_sum($sum["all"][$key]);
            
            if($type["type"]=="minus")
                 $type["sum"] = 0-$type["sum"];
            
            if($type["type"])
                {
                    $total_sum += $type["sum"];
                
                }
            
            
            
            foreach ($type["sspTransactionCategories"] as $cat_key => &$category) {
                $category["sum"] = array_sum($sum["cat"][$key][$cat_key]);
            }
        }

      
      if(isset($sum["limit"]))
      {     
        $arResult["total_sum"] = $total_sum;   //общая сумма
        $arResult["limit"] = max($sum["limit"]);   //для отрисовки таблицы - максимальное число строк  
      }
        return $arResult;
    }

}
