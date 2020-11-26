<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\selectize\SelectizeTextInput;
/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="own-books-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($book, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($book, 'description')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($book, 'tagNames')->widget(SelectizeTextInput::className(), [
        'loadUrl' => Url::to(['site/get-some-tags']),
        'queryParam' => 'tag', //имя входящего параметра у get-something
        'clientOptions' => [
            'plugins' => ['remove_button'],
            'valueField' => 'name',
            'labelField' => 'name',
            'searchField' => ['name'],
            'create' => false,
        ],
    ]) ?>

    <?= $form->field($book, 'authorNames')->widget(SelectizeTextInput::className(), [
        'loadUrl' => Url::to(['site/get-some-authors']),
        'queryParam' => 'author', //имя входящего параметра у get-something
        'clientOptions' => [
            'plugins' => ['remove_button'],
            'valueField' => 'name',
            'labelField' => 'name',
            'searchField' => ['name'],
            'create' => false,
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
