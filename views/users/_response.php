<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Button;
?>
<div class="row">
<?php
$form = ActiveForm::begin();
echo $form->field($model, 'response')->textarea(['rows' => 5, 'style' => 'resize:none;', 'placeholder' => 'Здесь вы можете оставить свой комментарий'])->label(false);
echo Html::a('Одобрить', ['site/index'], ['class' => 'btn btn-success']).' '.Html::a('Отклонить', ['site/index'], ['class' => 'btn btn-danger']);
$form = ActiveForm::end();
?>