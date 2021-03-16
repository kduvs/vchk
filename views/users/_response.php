<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Button;
?>
<div class="row">
<?php
$form = ActiveForm::begin([
    'method' => 'post',
    'action' => ['users/notifications', 'id' => $model->id]
]);
echo $form->field($model, 'response')->textarea(['rows' => 5, 'style' => 'resize:none;', 'placeholder' => 'Здесь вы можете оставить свой комментарий'])->label(false);
echo $form->field($model, 'id')->hiddenInput()->label(false);
echo Html::submitButton('Одобрить', ['class' => 'btn btn-success', 'value' => 'positive', 'name' => 'submit']).' '.Html::submitButton('Отклонить', ['class' => 'btn btn-danger', 'value' => ['negative', $model->id], 'name' => 'submit']);//Html::a('Отклонить', ['site/index'], ['class' => 'btn btn-danger']);
$form = ActiveForm::end();
?>