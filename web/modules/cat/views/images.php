<?php

use app\modules\cat\api\Cart;

if (isset($_SESSION["COMMENT_IMAGES"][$INDEX])) {
    $IMG = $_SESSION["COMMENT_IMAGES"][$INDEX];
    ?>

    <?php foreach ($IMG as $img) { ?>  
        <div class="uploaded_img_item" style="background-image: url(/uploads/cat_images/small/<?= $img ?>)"> 

            <div class="close"><i  index="<?= $INDEX ?>"  img="<?= $img ?>"   class="fa fa-times"></i></div> 
        </div> 
    <?php
    }
}
?>   
