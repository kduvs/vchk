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

    <?php $form = ActiveForm::end(); ?>