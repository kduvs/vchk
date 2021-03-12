<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;

?>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'deadline')->widget(DateTimePicker::className(), [
        'options' => ['placeholder' => 'Укажите время возврата'],
        'pluginOptions' => ['autoclose' => true]
    ]); ?>
    <?= $form->field($model, 'message')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-success']) ?>
    </div>
<?php $form = ActiveForm::end(); ?>