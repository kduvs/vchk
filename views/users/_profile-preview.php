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
                    'username',
                ],
                'template' =>
                    '<tr>
                        <!--<th>{label}</th>-->
                        <td style="width:90%; padding: 10px 15px;border-left: 5px solid #00aaff;">{value}</td>
                    </tr>',
            ]),
            Url::to(['profile', 'username' => $model->username])
        );
    ?>
 
</div>

