<?php
$this->title = Yii::t('easyii/reportsmain', 'Создать новый отзыв');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>