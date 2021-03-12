<?php

use yii\helpers\Html;

$this->title = 'Оформление возврата: ' . $book->title;
$this->params['breadcrumbs'][] = ['label' => 'Библиотека', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $book->title, 'url' => ['book', 'id' => $book->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-return">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-book', [
        'book' => $book,
    ]) ?>

</div>