<?php
use yii\bootstrap\Modal;
use kartik\form\ActiveForm;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
?>

<div class="row">
	<div class="col-sm-4">
		<div style="margin-top: 20px">
			<?php
			Modal::begin([
				'id' => 'book-modal',
                'header' => 'Я сделяль',
                'toggleButton' => ['label' => $label, 'class' => 'btn btn-primary', 'disabled' => abs($status) == 1],
			]);
			?>
            <?php $form = ActiveForm::begin([
				'id' => 'book-form',
				'enableAjaxValidation' => true,
				'action' => ['book'],
			]); ?>
			<div class="row" style="margin-bottom: 8px">
					<?= $form->field($model, 'deadline')->widget(DateTimePicker::className(), [
						'options' => ['placeholder' => 'Укажите время возврата'],
						'pluginOptions' => ['autoclose' => true]
					]); ?>
			</div>
			<?= $form->field($model, 'message')->textarea() ?>

            <div class="form-group">
                <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php $form = ActiveForm::end(); ?>
			<?php Modal::end(); ?>
		</div>
	</div>
</div>
