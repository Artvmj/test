<tr class="editable">

    <td class="text-left"> 
        <div style="display: inline-block;" class="dropdown"> 
            <button data-toggle="dropdown" type="button" class="btn    
                    dropdown-toggle catalog-filter" aria-expanded="true">
                <span></span>
                <i class="fa fa-caret-down"></i>
            </button> 
            <ul class="dropdown-menu catalog"> 

                <? foreach ($LIST as $item) { ?>
                    <li id="<?= $item["id"] ?>"  type="<?= $item["type"]["name"] ?>" > <a href="javascript:void(0)"><?= $item["name"] ?> </a></li>  
                <? } ?> 

            </ul>

        </div>

    </td>
    <td class="text-left">Тип</td>
    <td class="text-left  sum_relative "> 
        <input name="sum" type="text" value="">  

        <i class="fa fa-plus-circle"></i>
        <i class="fa fa-minus-circle"></i>


    </td>


</tr>






