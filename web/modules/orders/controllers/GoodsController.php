<?php
namespace app\modules\orders\controllers;

use Yii;

use yii\easyii\components\Controller;
use app\modules\orders\models\Good;

class GoodsController extends Controller
{
    public function actionDelete($id)
    {
        if(($model = Good::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/orders', 'Order deleted'));
    }
}