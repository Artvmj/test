<?php

namespace app\components;

use app\modules\user\models\User;
use app\modules\transaction_category\models\SspTransactionCategory;
use app\modules\transaction_type\models\SspTransactionType;
use app\modules\transaction\models\SspTransaction;

class Reports {

    static $user_id;

    /**
     * выводит список транзакций по типам 
     * @return array список транзакций
     * @throws \yii\base\Exception
     */
    public static function getList() {

        $data = SspTransactionCategory::find()
                        ->joinWith(['sspTransactions' => function($q) {
                        $q->from(SspTransaction::tableName() . ' t')->where(['t.user_id' => [4]]);
                    }], 'INNER JOIN')
                        ->asArray()->all();


        // echo "<pre> data ";  print_r( $data);   echo "</pre>";             



        return $data;
    }

    /**
     * Возвращает список транзакций определенного пользователя
     *
     * @param int $user_id
     *
     * @return array
     */
    public static function getTransaction($user_id) {

        $data = SspTransaction::find()
                ->where(['user_id' => $user_id])
                ->with(['category' => function($q) {
                $q->with('type');
            }], 'INNER JOIN')
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();

        return $data;
    }
    
    
      /**
     * Возвращает массив количества транзакций, сгруппированный по интервалу времени за  последний час
     *
     * @param int $user_id
     *
     * @return array
     */
    public static function getLastTransaction($user_id) {

         $interval = 120;
         $period = 3600;
     
     
         
          $data = SspTransaction::find() ->where(['user_id' => $user_id]) 
                ->select(['count(*) AS count', 'ROUND((CEILING(UNIX_TIMESTAMP(`date_create`) /'. $interval.') * '. $interval.')) AS timeslice'])
                ->groupBy(['timeslice'])    //ROUND(UNIX_TIMESTAMP(timestamp)/(15 * 60)) AS timekey
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();
        
        
         
        $time = time();
        $time_begin = $data[0]["timeslice"];
        
        $before =  round(($time - $time_begin)/$interval)+1;
        
        $before =  $before*$interval;
        
        $time_begin+=$before;
        
        
        
         $data = array_reduce( $data, function ($result, $item) 
          {   
             $result[$item["timeslice"]] = $item["count"];  return $result; 
             
         }, array()); 
   
       
        $result = array();
        
        for($i=$time_begin; $i>$time_begin-$period;    $i-=$interval)
        {  
              $result["x"][] =    date('h:i',$i) ;       //ось x  - время
              $result["y"][] =   intval( isset($data[$i])?$data[$i]:0)  ;//ось y  - количество транзакций
               
        }
          

        return $result;
        
         
        
    }
    
    
    
    
    

    public static function getTransactionByType($user_id) {

        self::$user_id = $user_id;
        
        
        $arResult = array(); 
        $arResult["data"] = SspTransactionType::find()
                ->joinWith(['sspTransactionCategories' => function($q) {
                $q->joinWith(['sspTransactions' => function($q) {
                $q->where(['user_id' => self::$user_id]);
            }], 'INNER JOIN');
            }], 'INNER JOIN')
                ->asArray()
                ->all();
            
            
            $arResult["sum"]   =  self::getCountSumByArray($arResult["data"]);
            

        return $arResult;
    }
    
    public static function getTransactionByCategories($user_id) {
 
          $arResult = SspTransaction::find()
                ->where(['user_id' => $user_id])
                ->select(['SUM(sum) AS sum', 'category_id'])
                ->groupBy(['category_id'])
                ->with(['category' => function($q) {
                 $q->with('type');
                           }], 'INNER JOIN')
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();
               
                           
                           
                           
                           
            
        $data = array();                   
               $sum = 0;            
        foreach ($arResult as $value) { 
            
         
               
            if($value["category"]["type"]["id"]==4)
                 $sum =   intval(0 - $value["sum"]) ;
             else if($value["category"]["type"]["id"]==3)
                 $sum =    intval($value["sum"]);
             
           
             
             
             if($sum!=0)       
              $data[] = $sum; 
                 
        }                 
                           
                          
                           
 
        return $data;
    }
    
    

    public static  function getCountSumByArray($list) {

        $sum = array();

        foreach ($list as $key => $type) {
                   $sum["limit"][$key] = count($type["sspTransactionCategories"]);

            foreach ($type["sspTransactionCategories"] as $cat_key => $category) {
                foreach ($category["sspTransactions"] as $transaction) {

                    $sum["cat"][$key][$cat_key][] = $transaction["sum"];
                    $sum["all"][$key][] = $transaction["sum"];
                }
            }
        } 
        
        return $sum;
    }

    public static function getUserList() {




        $data = SspTransactionCategory::find()
                        ->joinWith(['sspTransactions' => function($q) {
                        $q->from(SspTransaction::tableName() . ' t')->where(['t.user_id' => [4]]);
                    }], 'INNER JOIN')
                        ->asArray()->all();


        return $data;
    }

}
