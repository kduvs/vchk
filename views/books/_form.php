<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\selectize\SelectizeTextInput;
/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="books-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php 
        if(Yii::$app->user->can('manage')) {
            echo $form->field($model, 'owner_id')->dropDownList(
                \yii\helpers\ArrayHelper::map($owners, 'id', 'title')
            );
        }
    ?>
    
    <?= $form->field($model, 'tagNames')->widget(SelectizeTextInput::className(), [
        //$form->field($model, 'tagNames')->
        //'value' => $model->tagNames,    //должно вылазить в том случае, если мы апдейтим запись. Типа нужно отобрaжать те теги, которые уже стояли у этой записи
        'loadUrl' => Url::to(['site/get-some-tags']),
        'queryParam' => 'tag', //имя входящего параметра у get-something
        'clientOptions' => [
            'plugins' => ['remove_button'],
            'valueField' => 'name',
            'labelField' => 'name',
            'searchField' => ['name'],
            //'delimiter' => '6',
            'create' => false,
        ],
    ]) ?>

    <?= $form->field($model, 'authorNames')->widget(SelectizeTextInput::className(), [
        //$form->field($model, 'tagNames')->
        //'value' => $model->tagNames,    //должно вылазить в том случае, если мы апдейтим запись. Типа нужно отобрaжать те теги, которые уже стояли у этой записи
        'loadUrl' => Url::to(['site/get-some-authors']),
        'queryParam' => 'author', //имя входящего параметра у get-something
        'clientOptions' => [
            'plugins' => ['remove_button'],
            'valueField' => 'name',
            'labelField' => 'name',
            'searchField' => ['name'],
            //'delimiter' => '6',
            'create' => false,
        ],
    ]) ?>

    <?php /* = SelectizeTextInput::widget([
        'name' => 'Books[tagNames]',
        'value' => $model->tagNames,    //должно вылазить в том случае, если мы апдейтим запись. Типа нужно отоброжать те теги, которые уже стояли у этой записи
        'loadUrl' => Url::to(['site/get-something']),
        'queryParam' => 'tag',
        'clientOptions' => [
            'plugins' => ['remove_button'],
            'valueField' => 'name',
            'labelField' => 'name',
            'searchField' => ['name'],
            //'delimiter' => '6',
            'create' => false,
        ],
    ])*/ ?>

    <?= $form->field($model, 'qr_code')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
