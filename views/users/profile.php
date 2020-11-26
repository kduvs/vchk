<?php
 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = Yii::t('app', $model->username);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile">

    <h2><?= Html::encode($this->title) ?></h2>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'role',
        ],
    ]) ?>

    <h3><?= 'Список'.$substr.'книг'?></h3>

    <?= GridView::widget([
        'summary' => false,
        'dataProvider' => $books,
        'columns' => array_filter([
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'description:ntext',
            [
                'label' => 'Авторы',
                'attribute' => 'authorNamesFormat',
                'value' => function($data){
                    return $data->authorBehavior->objectNamesFormat;
                }
            ],
            [
                'label' => 'Теги',
                'attribute' => 'tagNamesFormat',
                'value' => function($data){
                    return $data->tagBehavior->objectNamesFormat;
                }
            ],
            //'qr_code',

            !Yii::$app->user->isGuest ? (
                Yii::$app->user->identity->getId() == $model->id ? ['class' => 'yii\grid\ActionColumn'] : false
            ) : false,

            // А ВООБЩЕ В СТРОКЕ ВЫШЕ ACTIONCOLUMNНУЖНО ВОТ ТАК ПОМЕНЯТЬ, ЧТОБЫ ССЫЛКИ РАБОТАЛИ
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'header' => 'Actions',
            //     'headerOptions' => ['style' => 'color:#337ab7'],
            //     'template' => '{view}{update}{delete}',
            //     'buttons' => [
            //     'view' => function ($url, $model) {
            //         return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
            //                     'title' => Yii::t('app', 'lead-view'),
            //         ]);
            //     },
    
            //     'update' => function ($url, $model) {
            //         return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
            //                     'title' => Yii::t('app', 'lead-update'),
            //         ]);
            //     },
            //     'delete' => function ($url, $model) {
            //         return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
            //                     'title' => Yii::t('app', 'lead-delete'),
            //         ]);
            //     }
    
            //     ],
            //     'urlCreator' => function ($action, $model, $key, $index) {
            //         if ($action === 'view') {
            //             $url ='index.php?r=client-login/lead-view&id='.$model->id;
            //             return $url;
            //         }
            //         if ($action === 'update') {
            //             $url ='index.php?r=client-login/lead-update&id='.$model->id;
            //             return $url;
            //         }
            //         if ($action === 'delete') {
            //             $url ='index.php?r=client-login/lead-delete&id='.$model->id;
            //             return $url;
            //         }
            //     }
            // ]
        ]),
    ]); ?>
 
</div>

