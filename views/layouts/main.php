<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => 'Семейный бюджет',
                'brandUrl' => "/",
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
           echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Home', 'url' => ['/site/index']],
                    ['label' => 'Админка', 'url' => ['/admin/']],
                    Yii::$app->user->isGuest ? (
                            ['label' => 'Login', 'url' => ['/auth']]
                            ) : (
                            '<li>'
                            . Html::beginForm(['logout/'], 'post', ['class' => 'navbar-form'])
                            . Html::submitButton(
                                    'Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link']
                            )
                            . Html::endForm()
                            . '</li>'
                            )
                ],
            ]);
            NavBar::end();
            ?>

            <div class="container">

                <table style="width:100%">
                    <tr>
                        <td width="200px;" style="vertical-align:top;"> 

                            <?php
                            echo Nav::widget([
                                'options' => ['class' => ''],
                                'items' => [
                                    ['label' => 'Транзакции', 'url' => ['/admin/transaction/index']],
                                    ['label' => 'Категории транзакций', 'url' => ['/admin/transaction_category/index']],
                                    ['label' => 'Типы транзакций', 'url' => ['/admin/transaction_type/index']],
                                    ['label' => 'Пользователи', 'url' => ['/admin/user/index']],
                                ],
                            ]);
                            ?> 

                        </td>
                        <td>

                            <?=
                            Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                               
                                
                                'homeLink' => [ 
                                    'label' => "Админка",
                                    'url' => "/admin",
                                 ],
                                 
                            ]);
                                  
                            
                             
                            
                                    
                                    
                            ?> 
                            
                            
                            
                            
                            <?= $content ?>



                        </td>
                    </tr>
                </table>




            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
