<?php

namespace app\modules\reports;

use app\modules\user\models\User;
use app\modules\transaction_category\models\SspTransactionCategory;
use app\modules\transaction_type\models\SspTransactionType;
use app\modules\transaction\models\SspTransaction;
use yii\helpers\ArrayHelper;



class Type {
 
    /**
     * Группирует транзакции по типу
     * возвращает общую сумму и количество транзакций  по типам
     *
     * @param int $user_id
     *
     * @return array
     */
    
    public static function getList($user_id) { 
  
        
        $result = [];
        
        
         $arResult = SspTransaction::find()
                ->where(['user_id' => $user_id]) 
                ->from(SspTransaction::tableName() . ' tr')
                ->select(['SUM(sum) AS sum','COUNT(*) AS count', "tr.id", "tr.category_id"])
                ->joinWith(['type' => function($q) { $q->from(SspTransactionType::tableName() . ' t')->groupBy(['t.type'])  ;  }], 'INNER JOIN') 
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();   
                
                
              
                
                
                
          
           $result['sum'] =     ArrayHelper::getColumn($arResult, function ($element)   {   return ['y'=> intval($element['sum']),  'name'=>  $element['type']['name']]; });   
           $result['count'] =   ArrayHelper::getColumn($arResult, function ($element) {     return ['y'=> intval($element['count']),  'name'=>  $element['type']['name']]; });  
       
           $result['table'] =   ArrayHelper::getColumn($arResult, function ($element) {    return  ['sum'=>  $element['sum'], 'type'=> $element['type']['type']]; }); 
           $result['table'] =   ArrayHelper::index($result['table'], 'type'); 
           
              $result['table']["minus"]["sum"] = isset($result['table']["minus"]["sum"])?$result['table']["minus"]["sum"]:"0";
              $result['table']["plus"]["sum"] = isset($result['table']["plus"]["sum"])?$result['table']["plus"]["sum"]:"0";
        
           
        return $result;
    }

}
