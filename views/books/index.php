<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BooksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Books', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            'description:ntext',
            [
                'label' => 'Владелец',
                'attribute' => 'owner_title',
                'value' => function($data){
                    return $data->owner->title;
                }
            ],
            // [
            //     'label' => 'Авторы',
            //     'attribute' => 'author.fio',
            //     'value' => function($data){
            //         $str = '';
            //         foreach($data->authors as $value){
            //             $str .= $value->fio.', ';
            //         }
            //         $str = substr($str, 0, -2);
            //         return $str;
            //     }
            // ],
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
