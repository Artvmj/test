<?php
use app\modules\orders\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('easyii/orders', 'Order') . ' #' . $order->primaryKey;
$this->registerCss('.shopcart-view dt{margin-bottom: 10px;}');

$states = Order::states();
unset($states[Order::STATUS_BLANK]);

$module = $this->context->module->id;

$this->registerJs('
var oldStatus = '.$order->status.';
$("#order-status").change(function(){
    if($(this).val() != oldStatus){
        $("#notify-user").slideDown();
    } else {
        $("#notify-user").slideUp();
    }
});
');
?>
<?= $this->render('_menu') ?>


<dl class="dl-horizontal shopcart-view">
 

    <dt><?= Yii::t('easyii', 'Name') ?></dt>
    <dd><?= $profile->name ?></dd>

    <dt>Город</dt>
    <dd><?= $profile->location ?></dd>
    
    
     <dt>Улица</dt>
     <dd><?= $profile->street ?></dd>
    
     <dt>Индекс</dt>
     <dd><?= $profile->index ?></dd>
    

    <?php if($this->context->module->settings['enablePhone']) : ?>
        <dt><?= Yii::t('easyii/orders', 'Phone') ?></dt>
        <dd><?= $profile->phone ?></dd>
    <?php endif; ?>

    <?php if($this->context->module->settings['enableEmail']) : ?>
        <dt><?= Yii::t('easyii', 'E-mail') ?></dt>
        <dd><?= $user->email ?></dd>
    <?php endif; ?>
        
        
        
        
        

    <dt><?= Yii::t('easyii', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($order->time, 'medium') ?></dd>
    
    
    
    
    
 
  
</dl>
<br><br>

    <?= Html::beginForm() ?>
     
    <table>
        
        <tr>
            <td width="100px"><b>Статус</b></td>
            <td width="200px" ><?= Html::dropDownList('status', $order->status, $states, ['id' => 'order-status']) ?> </td>
            <td width="300px" ><?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?></td> 
        </tr> 
    </table>
        
               
                 
     <?= Html::endForm() ?>


<hr>
<h3><?= Yii::t('easyii/orders', 'Items') ?></h3>
<table class="table table-bordered">
    <thead>
        <th><?= Yii::t('easyii', 'Title') ?></th>
        
      <?php if(false){   ?>  
        <th>Аксессуары</th>
      <?php  }  ?>  
        
        <th width="80"><?= Yii::t('easyii/orders', 'Count') ?></th>
        <th width="80"><?= Yii::t('easyii/orders', 'Discount') ?></th>
        <th width="150"><?= Yii::t('easyii/orders', 'Price') ?></th>
        <th width="30"></th>
    </thead>
    <tbody>
        <?php foreach($goods as $good) :
            
            $a = "";
        
         if(isset($good->item->title))
            $a =   Html::a($good->item->title, ['/admin/'.$module.'/a/attr', 'id' => $good->good_id]) 
          
             ?>
            <tr>
                <td><?=$a?></td>
                
                <?php if(false){   ?>  
                <td>   <a href="<?= Url::to(['/admin/'.$module.'/a/attr', 'id' => $good->good_id]) ?>">посмотреть</a>   </td>
                <?php  }  ?>  
                
                
                <td><?= $good->count ?></td>
                <td><?= $good->discount ?></td>
                <td>
                    <?php if($good->discount) : ?>
                        <b><?= round($good->price * (1 - $good->discount / 100)) ?></b>
                        <strike><small class="smooth"><?= $good->price ?></small></strike>
                    <?php else : ?>
                        <b><?= $good->price ?></b>
                    <?php endif; ?>
                </td>
                <td><a href="<?= Url::to(['/admin/'.$module.'/goods/delete', 'id' => $good->primaryKey]) ?>" class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h2 class="text-right"><small><?= Yii::t('easyii/orders', 'Total') ?>:</small> <?= $order->cost ?></h2>
