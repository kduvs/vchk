<?php
 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

?>
<div class="user-profile-preview">
    <h3><center><?= Html::a($model->title, Url::to(['book', 'id' => $model->id])); ?></center></h3>
    <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'description',
                [
                    'label' => 'Владелец',
                    'value' => $model->owner->title,         
                ],
            ],
            'template' =>
                '<tr> 
                    <th>{label}</th>
                    <td style="width:90%; padding: 10px 15px;border-left: 5px solid #00ffaa;">{value}</td>
                </tr>',
        ])
    ?>
    
 
</div>
