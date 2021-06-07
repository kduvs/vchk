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
        'brandLabel' => Yii::$app->name,
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
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? ['label' => 'Signup', 'url' => ['/site/signup']] : false,
            Yii::$app->user->isGuest ? ['label' => 'Login', 'url' => ['/site/login']] : 
                ([
                    'label' => '<img src="data:image/png;base64,'.base64_encode($imagine->getImagine()->open($source)->resize(new Box(18, 18))).'" >',
                    'items' => [
                        ['label' => 'Профиль('.Yii::$app->user->identity->username.')', 'url' => ['/users/profile'], 'options' => ['style' => 'width: 200px;']], //достаточно у одной колонки поменять ширину
                        ['label' => 'Выйти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],    
                    ],
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
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
