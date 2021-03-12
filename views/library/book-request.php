<?php
 
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('app', $model->book->title);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile">

    <h2><?= Html::encode($this->title) ?></h2>
    <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'Название экземпляра',
                    'value' => $model->book->title,
                    'contentOptions' => ['class' => 'bg-red'],     // настройка HTML атрибутов для тега, соответсвующего value
                    'captionOptions' => ['tooltip' => 'Tooltip'],
                ],
                'deadline:datetime',
                'message:html',
            ],
            'template' =>
                '<tr>
                    <!--<th>{label}</th>-->
                    <td style="width:90%; padding: 10px 15px;border-left: 5px solid #00aaff;">{value}</td>
                </tr>',
        ]);
    ?>
 
</div>

