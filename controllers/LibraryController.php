<?php

namespace app\controllers;

use Yii;
use app\models\Books;
use app\models\BooksSearch;
use app\models\User;
use app\models\Owners;
use app\models\Authors;
use app\models\BookAuthor;
use app\models\LogIssuing;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

class LibraryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'cancel' => ['POST'],
                ],
            ],
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        // $searchModel = new BooksSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // return $this->render('index', [
        //     'searchModel' => $searchModel,
        //     'dataProvider' => $dataProvider,
        // ]);

        $dataProvider = new ActiveDataProvider([
            'query' => Books::find(),
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    // public function actionAjaxModal()
    // {
    //         $model = new LogIssuing();
    //         if ($model->load(Yii::$app->request->post())) {
    //             if ($model->save()) {
    //                 Yii::$app->getSession()->setFlash('success', 'Запрос подан');
    //                 //return $this->render('book', ['book' => $book, 'status' => 1]);
    //                 return $this->goBack();
    //             } else {
    //                 Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
    //                 return \yii\widgets\ActiveForm::validate($model);
    //             }
    //         }
    // }

    public function actionBook($id)
    {
        if (($book = Books::findOne($id)) !== null) {

            $model = new LogIssuing();
            // if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //     Yii::$app->getSession()->setFlash('success', 'Запрос подан');
            //     return $this->render('book', ['book' => $book, 'status' => 1]);
            // }

            $last_record = LogIssuing::find()
                ->where(['book_id' => $id])
                ->andWhere(['not', ['issue_time' => null]])
                ->andWhere(['not', ['deadline' => null]])
                ->andWhere(['return_time' => null])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if($last_record != null)
            {
                if($last_record->taker_id == Yii::$app->user->identity->getId())
                {
                    return $this->render('book', ['book' => $book, 'status' => 1, 'label' => 'вернуть']); //в ожидании
                }
                return $this->render('book', ['book' => $book, 'status' => -1, 'label' => 'занята до '.$last_record->deadline]); //занята до $last_record->deadline
            }

            // $count = LogIssuing::find()->where(['issue_time' => null])->andWhere(['book_id' => $id])->count(); //находим те заявки, которые еще не одобрили (issue_time => null) 
            // if($count != 0){
            //     $label = $count.' заявок';
            // } else {
            //     $label = 'подать запрос';
            // }
            // return $this->render('book', ['book' => $book, 'status' => 0, 'label' => $label]); //$count заявок
            $last_record = LogIssuing::find()
                ->where(['book_id' => $id])
                ->andWhere(['taker_id' => Yii::$app->user->identity->getId()])
                ->andWhere(['not', ['deadline' => null]])
                ->andWhere(['issue_time' => null])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if(!isset($last_record)){
                return $this->render('book', ['book' => $book, 'status' => 2, 'label' => 'Подать запрос']);
            }
            return $this->render('book', ['book' => $book, 'status' => -2, 'label' => 'В ожидании']);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBooking($book_id)
    {
        if(Yii::$app->user->can('bookBooking')){
            $book = $this->findBook($book_id);
            $model = new LogIssuing();
            $model->owner_id = $book->owner_id;
            $model->book_id = $book->id;
            $model->taker_id = Yii::$app->user->identity->getId();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Запрос подан');
                return $this->redirect(['book', 'id' => $book_id]);
            } else {
                return $this->render('booking', [
                    'model' => $model,
                ]);
            }
        }
        throw new ForbiddenHttpException;
    }

    public function actionBookRequest($id)
    {
        return $this->render('book-request', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionApproveRequest($logis_id)
    {
        $model = LogIssuing::findOne($logis_id);

        // А зачем нам проверять роль владельца? Вернуть проверку можно будет разве что в том случае если мы можем ограничивать возможности сотрудников (владельцев книг)
        // $userRole = current(ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id), 'name'));
        // if($userRole == 'employee') {
        //
        // } else throw new ForbiddenHttpException;
        
        if ($model->owner_id == Yii::$app->user->identity->owner_id) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Запрос одобрен');
                return $this->redirect(['users/notifications']);
            } else {
                return $this->render('approve-request', [
                    'model' => $model,
                ]);
            }
        } else throw new ForbiddenHttpException;
    }

    public function actionReturnBook($book_id)
    {
        $book = Books::findOne($book_id);
        $taker_id = Yii::$app->user->identity->getId(); // текущий владелец книги, который принимает возврат книги
        if ($book !== null && $book->owner_id == User::findOne($taker_id)) {
            $book->touch('return_time');
            $book->taker_id = $taker_id;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionEditBook($id)
    {
        $book = $this->findBook($id);

        if (!Yii::$app->user->can('updateOwnBook', ['book' => $book])) {
            throw new ForbiddenHttpException;
        }
        if ($book->load(Yii::$app->request->post()) && $book->save()) {
            return $this->redirect(['profile']);
        } else {
            return $this->render('edit-book', ['book' => $book]);
        }
    }

    public function actionAddBook()
    {
        if (!Yii::$app->user->can('createOwnBook')) {
            throw new ForbiddenHttpException;
        }

        $book = new Books();
        $book->owner_id = Yii::$app->user->identity->getId();

        if ($book->load(Yii::$app->request->post()) && $book->save()) {
            return $this->redirect(['users/profile']);
        } else {
            return $this->render('add-book', [
                'book' => $book,
            ]);
        }
    }

    public function actionDeleteBook($id)
    {
        $this->findBook($id)->delete();

        return $this->redirect(['users/profile']);
    }

    protected function findBook($id)
    {
        if (($book = Books::findOne($id)) !== null) {
            return $book;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModel($id)
    {
        if (($model = LogIssuing::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

