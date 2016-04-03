


 





<div id="custom-bootstrap-menu" class="navbar navbar-default " role="navigation">
     
    
       <?php 
                     $ar = explode("/", $_SERVER["REQUEST_URI"]);
                     $URL = $ar[1]; 
        ?>
    
    <div class="container-fluid"> 
        <div class="collapse navbar-collapse navbar-menubuilder">
            <ul class="nav navbar-nav navbar-left">
                <?php
                foreach ($PAGE as $p) {
                    if ($p->slug == "catalog") {
                        ?>
                        <li class="dropdown navbar-nav">
                            <a class="dropdown-toggle <?=($p->slug==$URL)?"active":""?>" data-toggle="dropdown"       href="/<?= $p->slug ?>/"><?= $p->title ?>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu"> 
                              <?php  foreach ($ARLINK as $link) {  ?> 
                                 
                                       <li   class="navbar-nav prevent_menu_default">
                                           
                                                <?php    $href =  ($link->children && is_array($link->children))?"javascript:void(0)":"/catalog/". $link->slug   ?>
                                            
                                                   <a href="<?=$href?>"><?=$link->title?></a>
                                       
                                       </li> 
                                       
                                        <?php  
                                          if($link->children && is_array($link->children))
                                          foreach ($link->children as $sublink) {  ?>  
                                                <li  style=" margin-left: 20px;"   
                                                     class="navbar-nav"><a   style="font-size: 16px;"   href="<?="/catalog/". $sublink->slug?>"><?=$sublink->title?></a></li>
                                       
                                        <?php  }  ?>  
                                       
                               <?php  }  ?>  
                            </ul>
                        </li> 
                    <?php } else { ?> 
                        <li> <a   class="<?=($p->slug==$URL)?"active":""?>"       href="/<?= $p->slug ?>/"><?= $p->title ?></a>   </li>  
                        <?php
                    }
                }
                ?>  
            </ul>
        </div>
    </div>
</div> 





