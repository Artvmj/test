<?php
$this->title = Yii::t('easyii/topmenu', 'Edit page');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>