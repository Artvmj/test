<?php

use yii\helpers\Url;

$this->title =  "Аксессуары";

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <?php if (IS_ROOT) : ?>
                    <th width="50">#</th>
                 <?php endif; ?>
                <th>Наименование</th>

                <th>Цена</th>
                <th width="30"></th>

            </tr>
        </thead>
        <tbody>
    <?php foreach ($data->models as $item) : ?>
                <tr> 
                    <td><?= $item->primaryKey ?></td> 
                    <td><a href="<?= Url::to(['/admin/' . $module . '/a/edit', 'id' => $item->primaryKey]) ?>"><?= $item->name ?></a></td> 
                    <td><?= $item->price ?></td>
                   
                     <?php  if(true) {  ?>  
                    <td><a href="<?= Url::to(['/admin/' . $module . '/a/delete', 'id' => $item->primaryKey]) ?>" 
                           class="glyphicon glyphicon-remove confirm-delete" title="<?= Yii::t('easyii', 'Delete item') ?>"></a></td>
                           
                    <?php  } ?>   
                           
                 
                             
               
                
                </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <?=
    yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ])
    ?>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>