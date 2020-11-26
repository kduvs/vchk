<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Экземпляры системы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="library-index">
    <br>
    <h2><center><?= Html::encode('Список экземпляров') ?></center></h2>
    <br>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_book-preview',
        // 'itemOptions' => ['class' => 'item'],
        'summary'=> false,
    ]); ?>
</div>