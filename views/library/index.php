<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\typeahead\Typeahead;
use yii\web\JsExpression;
use dosamigos\selectize\SelectizeTextInput;

$this->title = 'Экземпляры системы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="library-index">
    <br>
    <h2><center><?= Html::encode('Фильтр экземпляров') ?></center></h2>
    <br>
    <?php
        // if(isset($this->context->search) && $this->context->search != null)
        // {
        //     $model = $this->context->search;
        // }
        // else{
        //     $model = new BooksSearch();
        // }
        
        $form = ActiveForm::begin([
            'id' => 'firstRow',
            'method' => 'get',
            'action' => ['/library/index'],
            //'options' => ['class' => 'firstRowForm'],
        ])
    ?>
    <?= $form->field($model, 'authorNames'
        //, ['options'=>['class'=>'form-group col-sm-12']]
        )->widget(SelectizeTextInput::className(), [
        'loadUrl' => Url::to(['site/get-some-authors']),
        'queryParam' => 'author', //имя входящего параметра у get-something
        //'value' => $model->tagNames,
        'clientOptions' => [
            'placeholder' => 'По каким авторам искать', //Не знаете авторов? Так и не пишите в это поле / У каких авторов может быть то, что вам нужно?
            'plugins' => ['remove_button'],
            'valueField' => 'name',
            'labelField' => 'name',
            'searchField' => ['name'],
            //'delimiter' => '6',
            //'max-width' => '200px',
            'create' => false,
        ],
    ])->label('Выбрать авторов') ?>

    <?php
        $template = '<div><p class="repo-title">{{title}}</p>'.
            '<p class="repo-description">{{description}} <!-- style="ssdasdasdasdasdasdasdasdasdasdas" --></p></div>';
        echo $form->field($model, 'search_text')->widget(Typeahead::className(), [
            'pluginOptions' => ['highlight' => true],
            'name' => 'book_list', 
            'scrollable' => true,
            'options' => [
                'placeholder' => 'Начните писать название или описание экземпляра...',
                'autocomplete' => 'off', //минус всплывающие подсказки браузера
                //'class'=>'что-то может быть'
            ],
            'dataset' => [
                [
                    'prefetch' => Url::to(['site/main-book-search']),
                    'remote' => [
                        'url' => Url::to(['site/main-book-search']). '?q=%QUERY',
                        'wildcard' => '%QUERY'
                    ],
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'display' => 'value',
                    'limit' => 5,
                    'templates' => [
                        'notFound' => '<div class="text-danger" style="padding:0 8px">Записи не найдены.</div>',
                        'suggestion' => new JsExpression("Handlebars.compile('{$template}')")
                    ]
                ]
            ]
        ])->label('Искать по ключевым словам');
    ?>

    <table>
        <td>
            <?= $form->field($model, 'owner_id')->dropDownList(
                \yii\helpers\ArrayHelper::map($owners, 'id', 'title'),
                ['prompt' => 'Выберите владельца экземпляра'],
            )->label('Искать у конкретного преподавателя') ?>
            
        </td>
        <td>
            <?= Html::submitButton(
                'Найти', [
                    'class' => 'btn btn-success',
                    'style' => 'margin-top:10px; margin-left:10px' //просто была разница по высоте с другим столбцом
                ]
            )?>
        </td>
    </table>
    <?php ActiveForm::end() ?>
    
    <h2><center><?= (count($dataProvider->models) != 0)?Html::encode('Список найденных экземпляров'):Html::encode('Список найденных экземпляров') ?></center></h2>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_book-preview',
        // 'itemOptions' => ['class' => 'item'],
        'summary'=> false,
    ]); ?>
</div>