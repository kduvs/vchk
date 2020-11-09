<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="books-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'title',
            'description:ntext',
            //'owner_id',
            'qr_code',
            [
                'label' => 'Владелец',
                'attribute' => 'owner_title',
                'value' => function($data){
                    return $data->owner->title;
                }
            ],
            [
                'label' => 'Авторы',
                'attribute' => 'authorNamesFormat',
                'value' => $model->authorBehavior->objectNamesFormat,
                $model->authors
                //function($data){
                    // $str = '';
                    // foreach($data->authors as $value){
                    //     $str .= $value->title.', ';
                    // }
                    // $str = substr($str, 0, -2);
                    // return $str;
                //}
            ],
            [
                'label' => 'Теги',
                'attribute' => 'tagNamesFormat',
                'value' => $model->tagBehavior->objectNamesFormat,
            ],
        ],
    ]) ?>

</div>
