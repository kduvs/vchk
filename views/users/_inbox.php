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
    <h2><center><?= Html::encode('Входящие запросы') ?></center></h2>
    <br>
    <?php
    // print_r($inboxDataProvider);
    
    // exit(0);
    ?>
    <?= GridView::widget([
        'dataProvider' => $inboxDataProvider,
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
                'attribute' => 'taker',
                'value' => function($data){
                    return $data->taker->username;
                }
            ],
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
            // [
            //     'label' => 'Ваш ответ',
            //     'value' => function ($model) use ($form) {
            //         return $form->field($model, 'response')->textarea(['rows' => 5, 'style' => 'resize:none']);
            //     },
            //     'format' => 'raw',
            //     // 'contentOptions' => ['style' => 'width:20%;']
            // ],
            // [
            //     'value' => function ($data) {
            //         return Html::a('Одобрить', ['site/index'], ['class' => 'btn btn-success']);
            //     },
            //     'format' => 'raw',
            //     'contentOptions' => ['style' => 'width:auto;']
            // ],
            // [
            //     'value' => function ($data) {
            //         return Html::a('Отклонить', ['site/index'], ['class' => 'btn btn-danger']);
            //     },
            //     'format' => 'raw',
            //     'contentOptions' => ['style' => 'width:auto;']
            // ]
            [
                'label' => 'Ваш ответ',
                'value' => function ($model) {
                    return Collapse::Widget([
                        'items' => [
                            [
                                // 'label' => 'Развернуть',
                                // //'content => function($model){...}' or button with template
                                // 'value' => function ($model) {
                                //     return $this->render('_response', ['model' => $model]);
                                // },
                                // 'format' => 'raw',
                                // 'contentOptions' => ['style' => 'width:20%;']
                                'label' => 'Развернуть',
                                'content' => $this->render('_response', ['model' => $model]),
                                'format' => 'raw',
                            ]
                        ],
                    ]);
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:25%;']
            ]
            
        ]
    ]); ?>

</div>