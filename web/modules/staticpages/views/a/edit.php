<?php
$this->title = Yii::t('easyii/staticpages', 'Edit page');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>