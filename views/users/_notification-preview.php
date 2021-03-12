<?php
 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
?>
<div class="user-profile-preview">
    <?=
        Html::a(
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
            ]),
            Url::to(['library/book-request', 'id' => $model->id])
        );
    ?>
 
</div>