<?php
$this->title = Yii::t('easyii/cat', 'Create catalog');
?>
<?= $this->render('_menu', ['category' => $category]) ?>
<?= $this->render('_form', ['model' => $model, 'category' => $category ]) ?>