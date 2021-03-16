<?php

namespace app\controllers;

use app\models\User;
use app\models\Books;
use app\models\BooksSearch;
use app\models\LogIssuing;
use app\models\LogIssuingSearch;
use app\models\InboxLogIssuingSearch;
use app\models\SentLogIssuingSearch;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
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

    public function actionNotifications($id = null)
    {
        $model = new LogIssuing();
        //print_r(Yii::$app->request->post('LogIssuing'));
        if(Yii::$app->request->post('LogIssuing')){
            //print_r(Yii::$app->request->post('LogIssuing')['response']);
            //print_r(Yii::$app->request->post());
            //exit(0);

            //$model = LogIssuing::findOne($id);
            //if($model->load(Yii::$app->request->post())) {
            //    $model->save();
            //}
            $model->load(Yii::$app->request->post());
            //print_r($model);
            //exit(0);
            //print_r(Yii::$app->request->post(LogIssuing::className()));
            //echo '<br>';
            //print_r(Yii::$app->request->post(LogIssuing::className())[LogIssuing::$primaryKey]);
            $model = LogIssuing::findOne(Yii::$app->request->post('LogIssuing')['id']);
            if($model->load(Yii::$app->request->post())) {
                $model->save();
            }
            else {
                print_r(Yii::$app->request->post());
            }
            
        }
        
        
        if(Yii::$app->request->post('submit') === 'positive') {
            print_r(Yii::$app->request->post());
            exit(0);
        } elseif (Yii::$app->request->post('submit') === 'negative') {
            echo 'NEGATIVE';
        }
        $sentSearchModel = new SentLogIssuingSearch();
        $inboxSearchModel = new InboxLogIssuingSearch();
        return $this->render('notifications', [
            'sentSearchModel' => $sentSearchModel,
            'inboxSearchModel' => $inboxSearchModel,
        ]);
        
    }

    // public function actionInbox()
    // {
    //     $searchModel = new LogIssuingSearch();
    //     $searchModel->owner_id = Yii::$app->user->identity->owner_id;
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    //     return $this->render('notifications', [
    //         'dataProvider' => $dataProvider,
    //         'searchModel' => $searchModel
    //     ]);
    // }

    // public function actionSent()
    // {
    //     $searchModel = new LogIssuingSearch();
    //     $serachModel->taker_id = Yii::$app->user->identity->id;
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    //     return $this->render('notifications', [
    //         'dataProvider' => $dataProvider,
    //         'searchModel' => $searchModel
    //     ]);
    // }

    public function actionProfile($username = null) //переделать под правило без использования getColumn
    {
        //$books = Books::find()->where(['owner_id' => Yii::$app->user->identity->getId()])->all();
        $searchModel = new BooksSearch();
        
        //Yii::$app->request->queryParams
        if(!isset($username)){
            if(!Yii::$app->user->isGuest){
                //$model = User::findOne(Yii::$app->user->identity->getId());
                $model = Yii::$app->user->identity;
            } else {
                throw new ForbiddenHttpException;
            }
        } else {
            $model = $this->findModel($username);
            $RoleByUsername = current(ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($model->id), 'name'));
            if($RoleByUsername == 'admin'){
                $CurrentRole = current(ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id), 'name'));
                if($CurrentRole != $RoleByUsername){
                    throw new ForbiddenHttpException;
                }
            }
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
