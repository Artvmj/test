<?php
$this->title = Yii::t('easyii/topmenu', 'Create page');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>