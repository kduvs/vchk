<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Button;

$this->title = 'Список уведомлений';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
    //$searchModel->owner_id = Yii::$app->user->identity->owner_id;
    $inboxDataProvider = $inboxSearchModel->search(Yii::$app->request->queryParams);

    echo $this->render('_inbox', [
        'inboxDataProvider' => $inboxDataProvider,
        'searchModel' => $inboxSearchModel,
    ]);

    //$searchModel->owner_id = null;

    //$searchModel->taker_id = Yii::$app->user->identity->id;
    $sentDataProvider = $sentSearchModel->search(Yii::$app->request->queryParams);
    
    echo $this->render('_sent', [
        'sentDataProvider' => $sentDataProvider,
        'searchModel' => $sentSearchModel,
    ]);
?>