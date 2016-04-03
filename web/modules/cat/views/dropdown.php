     
<?php
$ar = explode(",", $default);


$list_def = array();

foreach ($ar as $item) {

    $list_def[] = intval($item);
}

 
$list_def = array_filter($list_def);

 sort($list_def);


?>


<div    style="display: inline-block;" class="dropdown decor"> 


    <input  type="hidden"  ajax="decor_add"  
            class="setdecor_quantity_dr"
            decor_id="<?= $ID ?>"
            name="value_<?= $ID ?>"  
            value="<?= $VALUE ?>"  
            code="<?= $CATALOG->item_id ?>"  
    /> 
    
    
    <button   id="<?= $ID ?>" data-toggle="dropdown" type="button" class="btn     dropdown-toggle decor-default"><?=$VALUE?></button> 
    <i class="fa fa-caret-down"></i>
    <ul class="dropdown-menu decor "> 
<?php foreach ($list_def as $item) { ?>  
            <li><?= $item ?></li> 
        <?php } ?>

    </ul>
</div> 