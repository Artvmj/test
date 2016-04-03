 <?php 
 use app\modules\cat\api\Catalog;
  use app\modules\cat\api\Cart;
 
 if($cart["num"]) { $word = "" ;?>

  <a href="/cart/">В корзине</a> <?= $cart["num"] ?>  <?=Cart::morph($cart["num"],  "товар", "товара", "товаров");  ?>  <br>
 <span>    на сумму <?=Catalog::price($cart["sum"]) ?></span> <i class="fa fa-rouble"></i>

 <?php } else { ?>
   
       <div class="empty">ваша корзина пуста</div> 

 <?php } ?>
