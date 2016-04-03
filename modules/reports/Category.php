<?php

namespace app\modules\reports;

use app\modules\user\models\User;
use app\modules\transaction_category\models\SspTransactionCategory;
use app\modules\transaction_type\models\SspTransactionType;
use app\modules\transaction\models\SspTransaction;

class Category {

    private static $user_id;
 

    /**
     * выводит список транзакций по категориям для постоения диаграммы
     * @return array список транзакций
     * @throws \yii\base\Exception
     */

    public static function getList($user_id) {
         

        $arResult = SspTransaction::find()
                ->where(['user_id' => $user_id])
                ->select(['SUM(sum) AS sum', 'category_id'])
                ->groupBy(['category_id'])
                ->with(['category' => function($q) { $q->with('type'); }], 'INNER JOIN') 
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();
 

        $data = array();
        $sum = 0;



        foreach ($arResult as $value) {

            if (isset($value["category"]["type"]["id"]) && isset($value["sum"]) && isset($value["category"]["name"])) {
                 
                $type_id = $value["category"]["type"]["id"];

                if ($value["category"]["type"]["type"]=="minus")
                    $sum = intval(0 - $value["sum"]);
                else if ($value["category"]["type"]["type"]=="plus")
                    $sum = intval($value["sum"]);


                if ($sum != 0) {
                    $data["y"][] = $sum;
                    $data["x"][] = $value["category"]["name"];
                }
            }
        }

        
        
          // echo "<pre> arResult ";  print_r($value);   echo "</pre>";  
        
        

        return $data;
    }

}
