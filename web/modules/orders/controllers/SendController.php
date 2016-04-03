<?php
namespace app\modules\orders\controllers;

use Yii;
use app\modules\orders\api\Shopcart;
use app\modules\orders\models\Order;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Order();
        $request = Yii::$app->request;

        if($model->load($request->post())) {
            $returnUrl = Shopcart::send($model->attributes) ? $request->post('successUrl') : $request->post('errorUrl');
            return $this->redirect($returnUrl);
        } else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }
}