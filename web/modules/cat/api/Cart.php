<?php

namespace app\modules\cat\api;

use Yii;
use yii\web\Cookie;
use yii\data\ActiveDataProvider;
use yii\easyii\models\Tag;
use app\modules\cat\models\Category;
use app\modules\decor\api\Decor;
use yii\easyii\widgets\Fancybox;
use yii\widgets\LinkPager;
//use app\modules\cat\api\Catalog; 
use app\modules\cat\models\Item;
use yii\helpers\ArrayHelper;
use app\modules\sizes\api\Sizes;
use yii\easyii\helpers\Image;
use app\modules\cat\api\Catalog;
use app\modules\color\models\Color;

class Cart extends \yii\easyii\components\API {

    public function api_getfromcookie() {
        $cart = array();

        if (isset($_COOKIE['emerald_cart'])) {
            $cart = $_COOKIE['emerald_cart'];
            $cart = json_decode($cart, true);
        }



        //	$cookies = \Yii::$app->request->cookies; //9988655 
        //   $cart = $cookies->getValue('emerald_cart');
        //$cart = isset($_SESSION['emerald_cart'])?$_SESSION['emerald_cart']:"";
        //$cart = json_decode($cart, true);

        return $cart;
    }

    public function api_decor($category_id) {

        $request = \Yii::$app->request->post();
        $ID = ArrayHelper::getValue($request, "ID");

        $cart = $this->api_getfromcookie();

        $arDecor = Category::findOne($category_id)->decor;




        $res = array();

        foreach ($arDecor as $value) {
            $res[] = array(
                "text_id" => $value->text_id,
                "price" => $value->price,
                   "list" => $value->slug,
                "name" => $value->name,
                "quantity" => isset($cart[$ID]["DECOR"][$value->text_id]) ? $cart[$ID]["DECOR"][$value->text_id] : 0
            );
        }


        if (!count($res)) {

            $arDecor = Decor::all();

            foreach ($arDecor as $value) {

                if (isset($cart[$ID]["DECOR"][$value["text_id"]])) {
                    $res[] = array(
                        "text_id" => $value["text_id"],
                        "price" => $value["price"],
                        "name" => $value["name"],
                        "quantity" => $cart[$ID]["DECOR"][$value["text_id"]]
                    );
                }
            }
        }


        return $res;
    }

    public function api_sizes() {

        $request = \Yii::$app->request->post();
        $ID = ArrayHelper::getValue($request, "ID");
        $cart = $this->api_getfromcookie();

        $arSizes = Sizes::all();

        $arSizes = array_reduce($arSizes, function ($result, $item) {
            $result[$item["sizes_id"]] = $item;
            return $result;
        }, array());

        $result = array();


        foreach ($cart[$ID]["SIZES"] as $id => $size) {

            $arSizes[$id]["size"] = $size;
            $result[] = $arSizes[$id];
        }

        return $result;
    }

    public function api_colors($INDEX) {

        $request = \Yii::$app->request->post();
        $ID = ArrayHelper::getValue($request, "ID");

        $cart = $this->api_getfromcookie();

        $arColor = array();

        if (isset($cart[$INDEX]["CODE"])) { 
            $objColor = Item::findOne($cart[$INDEX]["CODE"])->color; 
            foreach ($objColor as $color) { 
                $selected = "N"; 
                if ($color->id == $cart[$ID]["COLOR"])
                    $selected = "Y"; 
                  $arColor[$color->id] = ["NAME" => $color->title, "HEX" => $color->hex, "SELECTED" => $selected];
            }
        }
 
        return $arColor;
    }

    public function api_deleteitem($ID = false) {

        if (!$ID) {
            $request = \Yii::$app->request->post();
            $ID = ArrayHelper::getValue($request, "ID");
        } else {

            $ID = $ID - 1;
        }


        $tmp = $this->api_getfromcookie();
        $counter = 0;


        foreach ($tmp as $ar) {

            if ($counter != $ID)
                $arCart[] = $ar;

            $counter ++;
        }


        $arCart = isset($arCart) ? $arCart : [];


        $this->api_writecookie($arCart);


        return $this->api_product();
    }

    public function api_savedecortocart() {  //
        
        
        $request = \Yii::$app->request->post();
        $code = ArrayHelper::getValue($request, "code");
        $VALUE = ArrayHelper::getValue($request, "VALUE");
        $comment = ArrayHelper::getValue($request, "comment");

        $error = "";
        
        
        

        if (!isset($_SESSION["ORDER"][$code]['DECOR']))
            $_SESSION["ORDER"][$code]['DECOR'] = array(); 
        
            $VALUES = array_reduce($VALUE, function ($result, $item) {    //получение данных из сериализованного jquery массива
            $i = explode("_", $item["name"]);
            if (isset($i[1]))
                $result[$i[1]] = $item["value"];
            return $result;
        }, array());
        
         
        if (count($VALUES) != count(array_filter($VALUES))) {
            
            $error = array_diff($VALUES, array_filter($VALUES));
            
            $error = implode(", #", array_keys($error));
            if ($error)
                $error = "#" . $error;
            
            
        }
         

        if (!$error) {
           

            if ($comment)
                $_SESSION["COMMENT"][$code]['DECOR'] = $comment;
        }

        
        
        
        return json_encode($error);  
        
        
        
    }
    
    
    
    /*
     public function api_savedecortocart() {   
        
           $request = \Yii::$app->request->post();  
           $code =   ArrayHelper::getValue($request, "code"); 
           $VALUE =   ArrayHelper::getValue($request, "VALUE");
        
           $error = "";
           

        $VALUES = array_reduce($VALUE, function ($result, $item) {
            $i = explode("_", $item["name"]);
            if (isset($i[1]))
                $result[$i[1]] = $item["value"];
            return $result;
        }, array());

        
                 
        
        if (count($VALUES) != count(array_filter($VALUES))) {
            $error = array_diff($VALUES, array_filter($VALUES));
            $error = implode(", #", array_keys($error));
            if ($error)
                $error = "#" . $error;
        }


        if (!$error) 
            $_SESSION["ORDER"][$code]['DECOR'] = $VALUES;

        
      

        return json_encode($error);
    }  */
    
    
    
    
    
    
    
    
    
    

    public function api_savecolor() {  //
        $request = \Yii::$app->request->post();



        $code = ArrayHelper::getValue($request, "code");
        $VALUE = ArrayHelper::getValue($request, "VALUE");
        $comment = ArrayHelper::getValue($request, "comment");


        $error = "";

        if (!isset($_SESSION["ORDER"][$code]['COLOR']))
            $_SESSION["ORDER"][$code]['COLOR'] = array();


        if (!$VALUE)
            $error[] = "заполните цвет";
        else
            $_SESSION["ORDER"][$code]['COLOR'] = $VALUE;


        return json_encode($error);
    }
    
    
    
    
    
    
    
    
    public function api_setimage($ID, $IMG) {
 
               if(!isset($_SESSION["COMMENT_IMAGES"]))  $_SESSION["COMMENT_IMAGES"] = array(); 
                   $_SESSION["COMMENT_IMAGES"][$ID][] =  $IMG;
               
                 /*
                $arCart = array();

               //$tmp = $this->api_getfromcookie();
                
                 
                $counter = 0;
                foreach ($tmp as $ar) {
                    if ($counter == $ID)
                    {   
                        if(!isset($ar["IMG"]))  $ar["IMG"] = array();
                        $ar["IMG"][] = $IMG; 
                    } 
                    
                    $arCart[] = $ar;
                    $counter ++;
                }
 
                $this->api_writecookie($arCart); */
            
    }
    
    
     public function api_getimage($ID) { 
              
               $res = array();
                $tmp = $this->api_getfromcookie(); 
                $counter = 0; 
                foreach ($tmp as $ar) {
                    if ($counter == $ID && isset($ar["IMG"]))
                    {   
                         $res  =  $ar["IMG"]; 
                    }  
                    
                     $counter++; 
                }
                
                
                return   $res; 
    }
    
    
    
     public function api_deleteimage($ID, $IMG) { 
               
            if(!isset($_SESSION["COMMENT_IMAGES"][$ID]))   return; 
                     $_SESSION["COMMENT_IMAGES"][$ID] =   array_diff($_SESSION["COMMENT_IMAGES"][$ID], array($IMG));
          
    }
    
    
    
    
    

    public function api_setquantity() {

        $request = \Yii::$app->request->post();

        $ID = ArrayHelper::getValue($request, "ID");
        $VALUE = ArrayHelper::getValue($request, "VALUE");
        $TYPE = ArrayHelper::getValue($request, "type");



        if ($VALUE && $TYPE) {
            if ($TYPE == "view") {
                $_SESSION["ORDER"][$ID]['QUANTITY'] = $VALUE;

                $source = array();

                $source[$ID] = $_SESSION["ORDER"][$ID];
                $source[$ID]["CODE"] = $ID;





                echo Catalog::price($this->api_sum($source));
                return;
            }

            //file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/log.txt", print_r(array("1tmp " => [$VALUE , $TYPE]), true), FILE_APPEND | LOCK_EX);



            if ($TYPE == "cart") {
                $arCart = array();

                $tmp = $this->api_getfromcookie();

                //   file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/log.txt", print_r(array("1tmp " => $tmp), true), FILE_APPEND | LOCK_EX);


                $counter = 0;
                foreach ($tmp as $ar) {
                    if ($counter == $ID)
                        $ar["QUANTITY"] = $VALUE;
                    $arCart[] = $ar;
                    $counter ++;
                }

                //  file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/log.txt", print_r(array("1arCart " => $arCart), true), FILE_APPEND | LOCK_EX);


                $this->api_writecookie($arCart);
            }
        }
    }

    public function api_savesizestocart() {  //
        $request = \Yii::$app->request->post();

        $code = ArrayHelper::getValue($request, "code");
        $VALUE = ArrayHelper::getValue($request, "VALUE");

        $error = "";

        $VALUES = array_reduce($VALUE, function ($result, $item) {
            $i = explode("_", $item["name"]);
            if (isset($i[1]))
                $result[$i[1]] = $item["value"];
            return $result;
        }, array());




        if (count($VALUES) != count(array_filter($VALUES))) {
            $error = array_diff($VALUES, array_filter($VALUES));
            $error = implode(", #", array_keys($error));
            if ($error)
                $error = "#" . $error;
        }


        if (!$error)
            $_SESSION["ORDER"][$code]['SIZES'] = $VALUES;

        return json_encode($error);
    }

    public function api_writecookie($ar) { //9988655
        $cookie_name = 'emerald_cart';
        $cookie_value = json_encode($ar, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);


        ob_start();
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 14), '/'); // 86400 = 1 day
        ob_end_flush();
    }

    public function api_addtocart() {



        $request = \Yii::$app->request->post();

        $code_item = ArrayHelper::getValue($request, "code");

        $CART = array();
        $CART = $this->api_getfromcookie();

        if (isset($_SESSION["ORDER"])) {

            file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/log.txt", print_r(array(" ORDER " => $_SESSION["ORDER"]), true), FILE_APPEND | LOCK_EX);

            foreach ($_SESSION["ORDER"] as $code => $attr) {

                $attr["CODE"] = $code;

                if (isset($code) && $code == $code_item && isset($attr["DECOR"]) && isset($attr["SIZES"])) {
                    $CART[] = $attr;
                }
            }


            $this->api_writecookie($CART);
        }
    }

    public function api_morph($n, $f1, $f2, $f5) { // комментарий комментария комментариев
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20)
            return $f5;
        if ($n1 > 1 && $n1 < 5)
            return $f2;
        if ($n1 == 1)
            return $f1;
        return $f5;
    }

    public function api_setsizes() {

        $request = \Yii::$app->request->post();

        $code = ArrayHelper::getValue($request, "code"); //символьный код модели
        $ID = ArrayHelper::getValue($request, "ID");  //id выбранного размера
        $value = ArrayHelper::getValue($request, "value");


        //$_SESSION["ORDER"][$code]['CODE'] = $code;

        $_SESSION['SIZES_CART_' . $code][$ID] = $value;

        if ($ID) {

            $sizes = Sizes::getbyid($ID);

            echo json_encode(['SRC' => Image::thumb($sizes->image, 300), 'TEXT' => $sizes->short]);

            return;
        }
    }

    public function api_updatedecor() {

        $CART = array();
        $result = array();

        $CART = $this->api_getfromcookie();

        $request = \Yii::$app->request->post();
        $INDEX = ArrayHelper::getValue($request, "INDEX");
        $ID = ArrayHelper::getValue($request, "ID");
        $VALUE = ArrayHelper::getValue($request, "VALUE");

        $CART[$INDEX]["DECOR"][$ID] = $VALUE;

        $decor = Decor::getbyid($ID);
        $catalog = Item::findOne($CART[$INDEX]["CODE"]);


           
        
        $SUM = $this->api_sum([$CART[$INDEX]], true);
        $TOTAL_SUM = $this->api_sum([$CART[$INDEX]], false);
        
        
        
        

        $this->api_writecookie($CART);

        $result = array(
            
            "PRICE" => Catalog::price($decor->price), 
            "DECOR_SUM" => Catalog::price($decor->price * $VALUE), 
            "TOTAL" => Catalog::price($TOTAL_SUM), 
            "SUM" => Catalog::price($SUM), 
            "CATALOG_SUM" => Catalog::price($catalog->price * $VALUE)
                
                );

        echo json_encode($result);
    }

    public function api_updatesizes() {

        $CART = array();
        $result = array();

        $CART = $this->api_getfromcookie();

        $request = \Yii::$app->request->post();
        $INDEX = ArrayHelper::getValue($request, "INDEX");
        $ID = ArrayHelper::getValue($request, "ID");
        $VALUE = ArrayHelper::getValue($request, "VALUE");

        $CART[$INDEX]["SIZES"][$ID] = $VALUE;
        $this->api_writecookie($CART);
    }

       public function api_updatecolor() {

        $CART = array();
        $result = array();

        $CART = $this->api_getfromcookie();

        $request = \Yii::$app->request->post();
        $INDEX = ArrayHelper::getValue($request, "INDEX");
        $ID = ArrayHelper::getValue($request, "ID");
        $VALUE = ArrayHelper::getValue($request, "VALUE");

        $CART[$INDEX]["COLOR"]  =   $VALUE;
        
        $this->api_writecookie($CART);
    }
    
    
    
    public function api_adddecor() {

        $request = \Yii::$app->request->post();



        $code = ArrayHelper::getValue($request, "code");

        if ($code) {
            $catalog = Item::findOne($code);
            $section_id = $catalog->category_id;
        }

        $decor_id = ArrayHelper::getValue($request, "ID");
        $value = ArrayHelper::getValue($request, "VALUE");
        $ajax = ArrayHelper::getValue($request, "ajax");


        $comment = "";
        if (isset($_SESSION["COMMENT"][$code]['DECOR']))
            $comment = $_SESSION["COMMENT"][$code]['DECOR'];


        // if(!isset($_SESSION['DECOR_CART_' . $code])) $_SESSION['DECOR_CART_' . $code] = array();     




        if (!isset($_SESSION["ORDER"])) {
            $_SESSION["ORDER"] = array();
        }




        $_SESSION["ORDER"][$code]['DECOR'][$decor_id] = intval($value);
        $_SESSION["ORDER"][$code]['DECOR'] = array_filter($_SESSION["ORDER"][$code]['DECOR']);




        if ($ajax) {
            echo \Yii::$app->view->renderFile('@app/views/catalog/decor_dialog.php', //показать страницу аксессуаров
                    [
                'COMMENT' => $comment,
                'CATALOG' => $catalog,
                'DECOR' => Category::alldecor($section_id)->asArray()->all()]
            );
        } else {



            $CART[0]["DECOR"] = $_SESSION["ORDER"][$code]['DECOR'];
            $CART[0]["CODE"] = $code;
            $decor = Decor::getbyid($decor_id);
            $SUM = $this->api_sum([$CART[0]], true);
            $TOTAL_SUM = $this->api_sum([$CART[0]], false);

            $result = array("PRICE" => $decor->price, "DECOR_SUM" => Catalog::price($decor->price * $value), "TOTAL" => Catalog::price($TOTAL_SUM),
                "SUM" => Catalog::price($SUM), "CATALOG_SUM" => Catalog::price($catalog->price));

            echo json_encode($result);
        }





        return;
    }

    public function api_addcolor() {

        $request = \Yii::$app->request->post();
        $code = ArrayHelper::getValue($request, "code");



        $color_id = ArrayHelper::getValue($request, "ID");
        $value = ArrayHelper::getValue($request, "VALUE");
        $ajax = ArrayHelper::getValue($request, "ajax");



        if ($code) {
            $catalog = Item::findOne($code);
            $section_id = $catalog->category_id;
        }


        if (!isset($_SESSION["ORDER"])) {
            $_SESSION["ORDER"] = array();
        }

        //  $_SESSION["ORDER"][$code]['COLOR'][$decor_id] = intval($value); 
        // $_SESSION["ORDER"][$code]['COLOR'] = array_filter($_SESSION["ORDER"][$code]['DECOR']);





        if ($ajax) {
            echo \Yii::$app->view->renderFile('@app/views/catalog/color_dialog.php', //показать страницу аксессуаров
                    [
                'CATALOG' => $catalog,
                'COLOR' => $catalog->color]
            );
        } else {

            /*

              $CART[0]["DECOR"] = $_SESSION["ORDER"][$code]['DECOR'];
              $CART[0]["CODE"] = $code;
              $decor = Decor::getbyid($decor_id);
              $SUM = $this->api_sum([$CART[0]], true);
              $TOTAL_SUM = $this->api_sum([$CART[0]], false);

              $result = array(
              "PRICE" => $decor->price,
              "DECOR_SUM" => Catalog::price($decor->price*$value), "TOTAL" => Catalog::price($TOTAL_SUM),
              "SUM" => Catalog::price($SUM), "CATALOG_SUM" => Catalog::price($catalog->price));

              echo json_encode($result); */
        }





        return;
    }

    public function api_addsizes() {

        $request = \Yii::$app->request->post();

        $code = ArrayHelper::getValue($request, "code");

        if ($code) {
            $catalog = Item::findOne($code);
            $section_id = $catalog->category_id;
        }




        echo \Yii::$app->view->renderFile('@app/views/catalog/sizes_dialog.php', //показать страницу размеров
                [
            'CATALOG' => $catalog,
            'SIZES' => Category::allsize($section_id)->asArray()->one()]
        );



        return;
    }

    public function api_sum($source = false, $decor_only = false, $without_quantity = false) {

        if (!$source)
            $cart = $this->api_getfromcookie();
        else
            $cart = $source;

        $total_price = 0;

        if (is_array($cart)) {
            $arDecor = Decor::all();

            $arDecor = array_reduce($arDecor, function ($result, $item) {
                $result[$item["text_id"]] = $item;
                return $result;
            }, array());

            if (isset($cart) && count($cart)) {

                foreach ($cart as $index => $value) {

                    $code = ArrayHelper::getValue($value, "CODE");


                    if ($code) {
                        $price = 0;

                        if (!$decor_only) {
                            $catalog = Item::findOne($code);

                            if (isset($catalog->price)) { //если товар удалили, который есть в корзине, удалить запись в корзине
                                $price += $catalog->price;
                            } else {
                                $this->api_deleteitem($index + 1);
                                continue;
                            }
                        }


                        $QUANTITY = (isset($value["QUANTITY"])) ? $value["QUANTITY"] : 1;

                        if (isset($value["DECOR"])) {
                            foreach ($value["DECOR"] as $id => $num) {
                                if (isset($arDecor[$id]["price"]))
                                    $price += $arDecor[$id]["price"] * $num;
                            }
                        }

                        if (!$without_quantity)
                            $price *= $QUANTITY;

                        $total_price+=$price;
                    }
                }
            }
        }

        return $total_price;
    }

    public function api_product() {




        $cart = $this->api_getfromcookie();



        $arResult = array();


        if (is_array($cart)) {
            $arDecor = Decor::all();

            $arDecor = array_reduce($arDecor, function ($result, $item) {
                $result[$item["text_id"]] = $item;
                return $result;
            }, array());

            $total_price = 0;

            if (isset($cart) && count($cart)) {


                foreach ($cart as $index => $value) {
                    $price = 0;



                    if (isset($value["CODE"])) {
                        $code = $value["CODE"];
                        $catalog = Catalog::get($code);



                        if (isset($catalog->price)) { //если товар удалили, который есть в корзине, удалить запись в корзине
                            $price += $catalog->price;
                        } else {
                            $this->api_deleteitem($index + 1);
                            continue;
                        }








                        $QUANTITY = (isset($value["QUANTITY"])) ? $value["QUANTITY"] : 1;


                        if (isset($value["DECOR"])) {
                            foreach ($value["DECOR"] as $id => $num) {
                                if (isset($arDecor[$id]["price"]))
                                    $price += $arDecor[$id]["price"] * $num;
                            }
                        }


                        $cats = $catalog->getCat();

                        $URL = "/catalog/" . $cats->slug . "/" . $catalog->slug;

                        $ar = array(
                            "NAME" => $catalog->title,
                            "SLUG" => $catalog->slug,
                            "URL" => $URL,
                            "PRICE" => $price,
                            "ID" => $catalog->id,
                            "QUANTITY" => $QUANTITY
                        );



                        $price *= $QUANTITY;

                        $ar["SUM"] = $price;



                        $total_price+=$price;


                        $arResult["ITEMS"][$index] = $ar;
                        $arResult["TOTAL"] = $total_price;
                    }
                }
            }
        }


        return $arResult;
    }

    public function api_num() {


        $cart = $this->api_getfromcookie();




        $num = 0;


        if (isset($cart) && is_array($cart)) {

            $num = count(array_keys($cart));
        }



        return $num;
    }

}
