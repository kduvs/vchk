<?php

use yii\helpers\Html;

$this->title = 'Редактирование экземпляра: ' . $book->title;
$this->params['breadcrumbs'][] = ['label' => 'Библиотека', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $book->title, 'url' => ['book', 'id' => $book->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="books-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-book', [
        'book' => $book,
    ]) ?>

</div>