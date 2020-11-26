<?php

namespace app\controllers;

use app\models\User;
use app\models\Books;
use app\models\BooksSearch;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use Yii;

class UsersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionProfile($username = null)
    {
        //$books = Books::find()->where(['owner_id' => Yii::$app->user->identity->getId()])->all();
        $searchModel = new BooksSearch();
        
        //Yii::$app->request->queryParams
        if(!isset($username)){
            if(!Yii::$app->user->isGuest){
                $model = User::findOne(Yii::$app->user->identity->getId());
            } else {
                throw new ForbiddenHttpException;
            }
        } else {
            $model = $this->findModel($username);
        }

        $searchModel->owner_id = $model->owner_id;
        $books = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('profile', [
            'books' => $books,
            'model' => $model,
            'substr' => !isset($username) ? ' ваших ' : ' ',
        ]);
    }

    protected function findModel($username)
    {
        if (($model = User::find()->where(['username' => $username])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
