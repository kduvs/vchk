<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Пользователи сайта';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-index">
    <br>
    <h2><center><?= Html::encode('Список пользователей') ?></center></h2>
    <br>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_profile-preview',
        // 'itemOptions' => ['class' => 'item'],
        'summary'=> false,
    ]); ?>
</div>