<?php
 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\User;
use yii\bootstrap\Button;

$this->title = Yii::t('app', $book->title);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book">

    <h2><?= Html::encode($this->title) ?></h2>
    
    <?= DetailView::widget([
        'model' => $book,
        'attributes' => [
            'title',
            'description',
            [
                'label' => 'Владелец',
                'value' => $book->owner->title,         
            ],
            [
                'label' => 'Авторы',
                'attribute' => 'authorNamesFormat',
                'value' => function($data){
                    return $data->authorBehavior->objectNamesFormat;
                }
            ],
            [
                'label' => 'Теги',
                'attribute' => 'tagNamesFormat',
                'value' => function($data){
                    return $data->tagBehavior->objectNamesFormat;
                }
            ],
        ],
    ]) ?>
</div>

<?php
    if(User::findOne(Yii::$app->user->identity->getId())->owner_id == $book->owner_id){
        echo Html::a('Редактировать', ['edit-book', 'id' => $book->id], ['class' => 'btn btn-primary']).' ';
        echo Html::a('Удалить', ['delete-book', 'id' => $book->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить данный экземпляр?',
                'method' => 'post',
            ],
        ]);
    } else {
        //echo $this->render('_modal', ['book' => $book, 'model' => $model, 'status' => $status, 'label' => $label]);
        switch($status){
            case 1:
                echo Html::a($label, ['return-book', 'book_id' => $book->id], ['class' => 'btn btn-primary']);
                break;
            case 2:
                echo Html::a($label, ['booking', 'book_id' => $book->id], ['class' => 'btn btn-primary']);
                break;
            default:
                echo Button::widget([
                    'label' => $label,
                    'options' => [
                        'class' => 'btn btn-primary',
                        'disabled' => true,
                    ]
                ]);
                break;
        }
        //сделать проверку на то, свободна ли в данный момент эта книга, и в зависиммости от статуса выводить соотв. элементы (нет в наличии / получить)
    }
    //если захочется что-то более мудренное с html, то, чтобы с echo не мучаться, можешь вызвать render на форму с данной хтмл херней  
?>