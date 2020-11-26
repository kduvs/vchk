<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = 'Добавление экземпляра';
$this->params['breadcrumbs'][] = ['label' => User::findOne(Yii::$app->user->identity->getId())->username, 'url' => ['profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="own-books-create">

    <h1><?= Html::encode($this->title) ?></h1>

    да, так можно, представь себе

    <?= $this->render('_form-book', [
        'book' => $book,
    ]) ?>

</div>
