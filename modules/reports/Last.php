<?php

namespace app\modules\reports;

use app\modules\user\models\User;
use app\modules\transaction_category\models\SspTransactionCategory;
use app\modules\transaction_type\models\SspTransactionType;
use app\modules\transaction\models\SspTransaction;

class Last {

    static $interval = 120;
    static $period = 3600;

    /**
     * Возвращает массив количества транзакций, сгруппированный по интервалу времени за  последний час
     *
     * @param int $user_id
     *
     * @return array
     */
    
    public static function getList($user_id) {
        
        
        
       

        $data = SspTransaction::find()->where(['user_id' => $user_id])
                ->select(['count(*) AS count', 'ROUND((CEILING(UNIX_TIMESTAMP(`date_create`) /' . self::$interval . ') * ' . self::$interval . ')) AS timeslice'])
                ->groupBy(['timeslice'])    
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();

        $result = array();
        
    
        if(isset($data[0]))
       {
        $time = time();
        $time_begin = $data[0]["timeslice"];

        $before = round(($time - $time_begin) / self::$interval) + 1;   //период времени от текущего времени до первой транзакции
        $before = $before * self::$interval; 
        $time_begin+=$before;



        $data = array_reduce($data, function ($result, $item) {
            $result[$item["timeslice"]] = $item["count"];
            return $result;
        }, array());


      
        
       
         

        for ($i = $time_begin; $i > $time_begin - self::$period; $i-= self::$interval) {
              
            
            $result["x"][] = date('h:i', $i);                           //ось x  - время
            $result["y"][] = intval(isset($data[$i]) ? $data[$i] : 0); //ось y  - количество транзакций
        }
        
        
       }
        
        
        return $result;
    }

}
