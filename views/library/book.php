<?php
 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\User;

use yii\bootstrap\Modal;
use kartik\form\ActiveForm;
use kartik\datetime\DateTimePicker;

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
        //echo Html::a('Получить', ['booking', 'id' => $book->id], ['class' => 'btn btn-primary']);
        //сделать проверку на то, свободна ли в данный момент эта книга, и в зависиммости от статуса выводить соотв. элементы (нет в наличии / получить)
    }
    //если захочется что-то более мудренное с html, то, чтобы с echo не мучаться, можешь вызвать render на форму с данной хтмл херней
?>


<div class="row">
	<div class="col-sm-4">
		<div style="margin-top: 20px">
			<?php
			Modal::begin([
                'header' => 'Я сделяль',
                'toggleButton' => ['label' => $label, 'class' => 'btn btn-primary', 'disabled' => abs($status) == 1],
			]);
			?>
            <?php $form = ActiveForm::begin(); ?>
			<div class="row" style="margin-bottom: 8px">
					<?= $form->field($model, 'deadline')->widget(DateTimePicker::className(), [
						'options' => ['placeholder' => 'Укажите время возврата'],
						'pluginOptions' => ['autoclose' => true]
					]); ?>
			</div>
			<?= $form->field($model, 'message')->textarea() ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
            <?php $form = ActiveForm::end(); ?>
			<?php Modal::end(); ?>
		</div>
	</div>
</div>
