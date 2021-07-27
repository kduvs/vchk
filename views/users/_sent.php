<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Button;
?>

<div class="">
    <br>
    <h2><center><?= Html::encode('Исходящие запросы') ?></center></h2>
    <br>

    <?= GridView::widget([
        'dataProvider' => $sentDataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        //'showHeader' => false,
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn'
            ],
            [
                'label' => 'Название экземпляра',
                'attribute' => 'book',
                'value' => function($data){
                    return $data->book->title;
                }
            ],
            [
                'attribute' => 'deadline',
            ],
            [
                'label' => 'Имя пользователя',
                'value' => function($data){
                    return $data->taker->username;
                }
            ],
            [
                'attribute' => 'book_id',
            ],
            // [
            //     'label' => 'Имя владельца',
            //     'attribute' => 'owner',
            //     'value' => function($data){
            //         return $data->owner->username;
            //     }
            // ],
            [
                'attribute' => 'message',
                'value' => function ($data) {
                    return Collapse::Widget([
                        'items' => [
                            [
                                'label' => 'Развернуть',
                                'content' => $data->message,
                                'format' => 'raw',
                            ],
                        ],
                    ]);
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:27%;']
            ],
        ]
    ]); ?>

</div>