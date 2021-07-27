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
            'name',
            'surname',
            'username'
        ],
    ]) ?>

    <h3><?=$q==''?'':'Личный Qr-код'?></h3>
    
    <?= $q ?>
 
</div>

