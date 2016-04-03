<?php

namespace app\modules\orders\api;

use Yii;
use app\modules\cat\models\Item;
use app\modules\orders\models\Good;
use app\modules\orders\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\cat\api\Cart;
use app\modules\decor\models\Decor;
use app\modules\sizes\models\Sizes;
use app\modules\orders\models\Decor as Decorgood;
use app\modules\orders\models\Sizes as Sizesgood;
use app\modules\cat\models\Category;
use app\modules\eu\models\User;
use yii\helpers\ArrayHelper;
use app\modules\color\models\Color;




/**
 * Shopcart module API
 * @package app\modules\orders\api
 *
 * @method static GoodObject goods() Get list of added to shopcart goods as GoodObject objects.
 * @method static OrderObject order(int $id) Get an order by id as OrderObject
 * @method static string form(array $options = []) Returns fully worked standalone html form to complete order.
 * @method static array add(int $item_id, int $count = 1, string $options = '', boolean $increaseOnDuplicate = true) Adds item to shopcart, returns GoodObject attributes
 * @method static array remove(int $good_id) Removes good to shopcart
 * @method static void update(array $goods) Update shopcart. Array format [$good_id => $new_count]
 * @method static array send(array $goods) Completes users shopcart order and send to admin.
 * @method static array cost() returns total cost of current shopcart.
 */
class Shopcart extends \yii\easyii\components\API {

    const SENT_VAR = 'shopcart_sent';
    const ERROR_ITEM_NOT_FOUND = 1;
    const ERROR_CREATE_ORDER = 2;
    const ERROR_GOOD_DUPLICATE = 3;
    const ERROR_CREATE_GOOD = 4;
    const ERROR_ORDER_NOT_FOUND = 5;
    const ERROR_ORDER_EMPTY = 6;
    const ERROR_ORDER_UPDATE = 7;

    private $_order;
    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_goods() {
        return $this->order->goods;
    }

    public function api_order($id) {
        $order = Order::findOne($id);
        return $order ? new OrderObject($order) : null;
    }

    public function api_form($options = []) {
        $model = new Order;
        $model->scenario = 'confirm';
        $settings = Yii::$app->getModule('admin')->activeModules['orders']->settings;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();
        $form = ActiveForm::begin([
                    'action' => Url::to(['/admin/shopcart/send'])
        ]);

        echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current([self::SENT_VAR => 0]));
        echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current([self::SENT_VAR => 1]));

        echo $form->field($model, 'name');
        echo $form->field($model, 'address');

        if ($settings['enableEmail'])
            echo $form->field($model, 'email');
        if ($settings['enablePhone'])
            echo $form->field($model, 'phone');

        echo $form->field($model, 'comment')->textarea();

        echo Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-primary']);
        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_add($cart /* $value, $index */) {

 

        $transaction = \Yii::$app->db->beginTransaction();
        try {


            if (!$this->order->id) {

                if (isset(\Yii::$app->user->identity->id))
                    $this->order->model->user_id = \Yii::$app->user->identity->id;
                 
                
                if (!$this->order->model->save()) {
                    return ['result' => 'error', 'code' => self::ERROR_CREATE_ORDER, 'error' => 'Cannot create order. ' . $this->order->formatErrors()];
                }
                 
                
            }
 
            foreach ($cart as $index => $value) {
                
                
               

                $item_id = $value["CODE"];

                if (isset($item_id)) {

                    $count = isset($value["QUANTITY"]) ? $value["QUANTITY"] : 1;


                    $item = Item::findOne($item_id); 
                    
                  
                    if (isset($item)) { 

                        $good = false;


                        if ($good) {
                            $good->count += $count;
                        } else {

                            $cart = Cart::getfromcookie();
                            $price = Cart::sum(array($cart[$index]), false, true); //из внешнего массива, с аксессуарами, без количества


                            $comment = "";
                            if (isset($_SESSION["COMMENT"][$item->primaryKey]['DECOR']))
                                $comment = $_SESSION["COMMENT"][$item->primaryKey]['DECOR'];

                            $_SESSION["COMMENT"][$item->primaryKey]['DECOR'] = ""; 
 
                             $color_name = "";
                            
                            if(isset($cart[$index]["COLOR"]))
                            {  
                                 $color = Color::findOne($cart[$index]["COLOR"]);
                                 $color_name = $color->title; 
                            }
                            
                            
                            if(isset($_SESSION["COMMENT_IMAGES"][$index])) 
                            {
                                $imageFolder1 = $_SERVER["DOCUMENT_ROOT"] . "/uploads/cat_images/temp/";
                                $imageFolder2 = $_SERVER["DOCUMENT_ROOT"] . "/uploads/order_images/";
            
                                 $allImages = array();
                                
                                 foreach ($_SESSION["COMMENT_IMAGES"][$index] as $img) {
                                   
                                       $allImages[] =  $img;
                                     
                                      $file =      $imageFolder1. $img;
                                      $newfile = $imageFolder2. $img;
                                      copy($file, $newfile);
                                      
                                 } 
                                 $_SESSION["COMMENT_IMAGES"][$index] = array();
             
                            }
                             
                            
                            $allImages = implode("#", $allImages);
                             
                            $good = new Good([
                                'order_id' => $this->order->id,
                                'item_id' => $item->primaryKey,
                                'color_name'=>  $color_name,
                                'count' => (int) $count,
                                'comment' => $comment, 
                                'name' => $item->title,
                                'item_price' => $item->price,
                                'img'=> $allImages,
                                'price' => $price
                            ]);
                            
                            
                            
                             
                        }
 
                        if ($good->save()) {


                            $response = [
                                'result' => 'success',
                                'order_id' => $this->order->id,
                                'good_id' => $good->primaryKey,
                                'item_id' => $item->primaryKey,
                                'options' => $good->options,
                                'discount' => $good->discount,
                            ];



                            foreach ($value["DECOR"] as $id => $q) {

                                $decor = Decor::findOne($id);

                               
                                
                                
                                if (isset($decor)) {
                                    $params = [
                                        'good_id' => $id,
                                        'item_id' => $good->primaryKey,
                                        'count' => (int) $q,
                                        'name' => $decor->name,
                                        'price' => $decor->price
                                    ];
                                    $d = new Decorgood($params);
                                    $d->save();
                                }
                            }


                            foreach ($value["SIZES"] as $id => $q) {

                                $size = Sizes::findOne($id);
                                
                              

                                $params = [
                                    'good_id' => $id,
                                    'item_id' => $good->primaryKey,
                                    'name' => $size->title,
                                    'count' => (int) $q,
                                ];



                                $s = new Sizesgood($params);
                                $s->save();
                            }

                            $response['price'] = $good->price;
 
                        } else {
                            
                        }
                    }
                }
            }
            
                             Cart::writecookie([]);
 
                             $order_html = $this->api_orderhtml($this->order->id); 
                            
                             $this->api_sendmail($order_html);
            
            
            

            /////////////////////////////////////////////////////////////////
        } catch (Exception $e) {

           file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/log.txt", print_r(array("e1" => $e), true), FILE_APPEND | LOCK_EX); 

            return ['result' => 'error', 'code' => self::ERROR_CREATE_GOOD, 'error' => $good->formatErrors()];
            $transaction->rollBack();
        }
  
        return  $this->order->id ;
    }

    public function api_orderhtml($id) {

        ob_start();

        $order = Order::findOne($id);
        $goods = Good::find()->where(['order_id' => $order->primaryKey])->with('item')->asc()->all();
        $category = ArrayHelper::map(Category::find()->all(), 'category_id', 'slug');


        echo \Yii::$app->view->renderFile('@app/views/order/order_mail.php', [
            'category' => $category,
            'order' => $order,
            'goods' => $goods
        ]);


        return ob_get_clean();
    }

    public function api_remove($good_id) {
        $good = Good::findOne($good_id);
        if (!$good) {
            return ['result' => 'error', 'code' => 1, 'error' => 'Good not found'];
        }
        if ($good->order_id != $this->order->id) {
            return ['result' => 'error', 'code' => 2, 'error' => 'Access denied'];
        }

        $good->delete();

        return ['result' => 'success', 'good_id' => $good_id, 'order_id' => $good->order_id];
    }

    public function api_update($goods) {
        if (is_array($goods) && count($this->order->goods)) {
            foreach ($this->order->goods as $good) {
                if (!empty($goods[$good->id])) {
                    $count = (int) $goods[$good->id];
                    if ($count > 0) {
                        $good->model->count = $count;
                        $good->model->update();
                    }
                }
            }
        }
    }

    public function api_send($data) {
        $model = $this->order->model;
        if (!$this->order->id || $model->status != Order::STATUS_BLANK) {
            return ['result' => 'error', 'code' => self::ERROR_ORDER_NOT_FOUND, 'error' => 'Order not found'];
        }
        if (!count($this->order->goods)) {
            return ['result' => 'error', 'code' => self::ERROR_ORDER_EMPTY, 'error' => 'Order is empty'];
        }
        $model->setAttributes($data);
        $model->status = Order::STATUS_PENDING;
        if ($model->save()) {
            return [
                'result' => 'success',
                'order_id' => $this->order->id,
                'access_token' => $this->order->access_token
            ];
        } else {
            return ['result' => 'error', 'code' => self::ERROR_ORDER_UPDATE, 'error' => $model->formatErrors()];
        }
    }

    public function api_cost() {
        return $this->order->cost;
    }

    public function getOrder() {
        if (!$this->_order) {
            //  $access_token = $this->token;
            //  if(!$access_token || !($order = Order::find()->where(['access_token' => $access_token])->status(Order::STATUS_BLANK)->one())){
            $order = new Order();
            //  }

            $this->_order = new OrderObject($order);
        }
        return $this->_order;
    }

    /*    public function sendMessage($to, $subject, $view, $params = [])
      {

      $mailer = Yii::$app->mailer;
      $mailer->viewPath = $this->viewPath;
      $mailer->getView()->theme = Yii::$app->view->theme;

      if ($this->sender === null) {
      $this->sender = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
      }

      return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
      ->setTo($to)
      ->setFrom($this->sender)
      ->setSubject($subject)
      ->send();
      } */

    public function api_sendmail($text) {
        $text = "<br><br>" .
                "<br> " . $text;

         $user = User::findOne(12);
         $email_from = $user->email; 
         $email = \Yii::$app->user->identity->email;


        if (Yii::$app->mailer->compose()
                        ->setFrom($email_from)
                        ->setTo($email)
                         ->setSubject("Заказ успешно оформлен")
                        ->setHtmlBody($text)
                        ->setReplyTo($email_from)
                        ->send()) {
            
        }

        return;
    }

    public function getToken() {
        return Yii::$app->session->get(Order::SESSION_KEY);
    }

}
