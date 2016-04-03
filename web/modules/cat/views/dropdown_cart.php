     
<?php


$ar = explode(",", $item["list"]);


$list_def = array();

foreach ($ar as $i) {

    $list_def[] = intval($i);
}


$list_def = array_filter($list_def);

  sort($list_def);


?>


<div style="display: inline-block;" class="dropdown decor"> 


    <input  type="hidden"   index="<?= $INDEX ?>"    id="<?= $ID ?>"     value="<?= $item["quantity"] ?>"/> 
                                          
    <button data-toggle="dropdown" type="button" class="btn     dropdown-toggle decor-default"><?= $item["quantity"] ?></button> 
    <i class="fa fa-caret-down"></i>
    <ul class="dropdown-menu cart "> 
<?php foreach ($list_def as $i) { ?>  
            <li><?= $i?></li> 
        <?php } ?>

    </ul>
</div> 