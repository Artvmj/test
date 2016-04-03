<?php
use yii\helpers\Url;

$this->title = Yii::t('easyii/color', 'Pages');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <?php if(IS_ROOT) : ?>
                    <th width="50">#</th>
                <?php endif; ?>
                    <th>Название цвета</th>
               
                    <th>код</th>
                    <th>цвет</th>
                    
                    <th width="30"></th>
               
            </tr>
        </thead>
        <tbody>
            
    <?php foreach($data->models as $item) : ?>
            <tr>
                <?php if(IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td><a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->primaryKey]) ?>"><?= $item->title ?></a></td>
              
                    <td><?= $item->hex ?></td> 
                    <td>
                        
                      
                        <span class="btn" style="background-color: <?=$item->hex?>;"> </span>
                         
                     </td>
                      
                      
                      
                    <td>
                        
                        
                           <a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey]) ?>" class="glyphicon glyphicon-remove confirm-delete" 
                           title="<?= Yii::t('easyii', 'Delete item')?>">
                           </a>
                    </td>
               
            </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>