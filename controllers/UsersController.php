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
use Da\QrCode\QrCode;
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
        if(Yii::$app->request->post('LogIssuing')){
            $model->load(Yii::$app->request->post());
            
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

    public function actionProfile($username = null) //переделать под правило без использования getColumn
    {
        if(!isset($username)){
            if(!Yii::$app->user->isGuest){
                $model = Yii::$app->user->identity;
                $qrCode = (new QrCode('https://vk.com')) //пока пусть будет просто айди $model->id
                    ->setSize(250)
                    ->setMargin(5)
                    ->useForegroundColor(51, 153, 255);

                // $qrCode->writeFile(__DIR__ . '/code.png');

                // header('Content-Type: '.$qrCode->getContentType());
                // echo $qrCode->writeString();
                $q = '<img src="' . $qrCode->writeDataUri() . '">';

            } else throw new NotFoundHttpException('The requested page does not exist.');

        } else {
            $model = $this->findModel($username);
            $q = '';
        }
        
        return $this->render('profile', [
            'model' => $model,
            'q' => $q,
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
