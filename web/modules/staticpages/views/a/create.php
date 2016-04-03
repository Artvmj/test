<?php
$this->title = Yii::t('easyii/staticpages', 'Create page');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>