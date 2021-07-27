<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\imagine\Image;
use Imagine\Image\Box;
use app\assets\AppAsset;

$source="http://localhost/blog/ui/round-account-button-with-user-inside_icon-icons.com_72596.png";        
$imagine = new Image();

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Внутренние частные коллекции',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-fixed-top',
            'style' => 'background-color: #dfe2ed;' //dff2fd
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right', 'style' => 'background-color: #fff2fd;'],
        'encodeLabels' => False,
        'items' => array_filter([ //array_filter переигрывает и уничтожает false-итемы            
            // ['label' => 'Связь с администратором', 'url' => ['/site/contact']],
            ['label' => 'Литература', 'url' => ['/library/index']],
            Yii::$app->user->isGuest ? false : ['label' => 'Уведомления', 'url' => ['/users/notifications']],
            (Yii::$app->user->can('checkOwnList') or Yii::$app->user->can('manage')) ? ['label' => 'Выдача', 'url' => ['/users/issuing']] : false,
            Yii::$app->user->isGuest ? ['label' => 'Регистрация', 'url' => ['/site/signup']] : false,
            Yii::$app->user->isGuest ? ['label' => 'Вход', 'url' => ['/site/login']] : 
                ([
                    'label' => '<img src="data:image/png;base64,'.base64_encode($imagine->getImagine()->open($source)->resize(new Box(18, 18))).'" >',
                    'items' => array_filter([
                        ['label' => 'Профиль('.Yii::$app->user->identity->username.')', 'url' => ['/users/profile'], 'options' => ['style' => 'width: 200px;']], //достаточно у одной колонки поменять ширину
                        (Yii::$app->user->can('checkOwnList') or Yii::$app->user->can('manage')) ? ['label' => 'Частная коллекция', 'url' => ['/books/index']] : false,
                        ['label' => 'Выйти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                    ]),
                ]),
        ]),
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Created by KDUVS</p>

        <p class="pull-right"> <?= date('Y').' год'?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
