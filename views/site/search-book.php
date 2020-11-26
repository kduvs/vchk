<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\models\BooksSearch;
use yii\widgets\ActiveForm;
use kartik\typeahead\Typeahead;
use yii\web\JsExpression;
use dosamigos\selectize\SelectizeTextInput;
/* @var $this yii\web\View */

$this->title = 'Your Yii Application';
?>
<?php
    if(isset($this->context->search) && $this->context->search != null)
    {
        $model = $this->context->search;
    }
    else{
        $model = new BooksSearch();
    }
    $form = ActiveForm::begin([
        'id' => 'firstRow',
        'method' => 'get',
        'action' => ['/site/search-book'],
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
        'placeholder' => 'Не знаете авторов? Так и не пишите в это поле',
        'plugins' => ['remove_button'],
        'valueField' => 'name',
        'labelField' => 'name',
        'searchField' => ['name'],
        //'delimiter' => '6',
        //'max-width' => '200px',
        'create' => false,
    ],
])->label(false) ?>

<?php
    $template = '<div><p class="repo-title">{{title}}</p>'.
        '<p class="repo-description">{{description}} <!-- style="ssdasdasdasdasdasdasdasdasdasdas" --></p></div>';
    echo $form->field($model, 'search_text')->widget(Typeahead::className(), [
        'pluginOptions' => ['highlight' => true],
        'name' => 'book_list', 
        'scrollable' => true,
        'options' => [
            'placeholder' => 'Начните писать название книги ...',
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
    ])->label(false);
?>

<table>
    <td>
        <!-- <?= $form->field($model, 'owner_id')->dropDownList(
            \yii\helpers\ArrayHelper::map($owners, 'id', 'title')
        )->label(false) ?> -->
        
    </td>
    <td>
        <div class="secondRow clearfix">
            <?= Html::input('submit',null,'Найти', ['class' => 'peopleSubmit']) ?>
        </div>
    </td>
</table>
<?php ActiveForm::end() ?>
<br>
<br>
<?php
foreach($dataProvider->models as $k=>$val){
    echo 'Название книги: '.'<b>'.$val->title.'</b>'.'<br>';
    echo 'Описание: '.$val->description.'<br><br>';
} ?>
