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

    public function actionBook($id)
    {
        if (($book = Books::findOne($id)) !== null) {

            $model = new LogIssuing();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Запрос подан');
                return $this->render('book', ['book' => $book, 'status' => 1]);
            }

            $last_record = LogIssuing::find()
                ->where(['book_id' => $id])
                ->andWhere(['not', ['issue_time' => null]])
                ->andWhere(['not', ['deadline' => null]])
                ->andWhere(['return_time' => null])
                ->one();
            if($last_record != null)
            {
                if($last_record->recipient_id == User::findOne(Yii::$app->user->identity->getId()))
                {
                    return $this->render('book', ['book' => $book, 'status' => 1, 'model' => $model, 'label' => 'в ожидании']); //в ожидании
                }
                return $this->render('book', ['book' => $book, 'status' => -1, 'model' => $model, 'label' => 'занята до '.$last_record->deadline]); //занята до $last_record->deadline
            }
            
            $model->owner_id = $book->owner_id;
            $model->book_id = $book->id;
            $model->recipient_id = User::findOne(Yii::$app->user->identity->getId());

            $count = LogIssuing::find()->where(['issue_time' => null])->andWhere(['book_id' => $id])->count(); //находим те заявки, которые еще не одобрили (issue_time => null) 
            if($count != 0){
                $label = $count.' заявок';
            } else {
                $label = 'подать запрос';
            }
            return $this->render('book', ['book' => $book, 'status' => 0, 'model' => $model, 'label' => $label]); //$count заявок
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // public function actionBooking($id = null)
    // {
    //     if(Yii::$app->user->can('bookBooking')){

    //         if(!isset($id)){
    //             return $this->render('booking'); //if(!isser($model)) in view
    //         }
            
    //         $book = Books::findOne($id);
    //         $model = new LogIssuing();
    //         $model->owner_id = $book->owner_id;
    //         $model->book_id = $book->id;
    //         $model->recipient_id = User::findOne(Yii::$app->user->identity->getId());
            
    //         return $this->render('booking', [
    //             'model' => $model,
    //         ]);
    //     }

    //     throw new ForbiddenHttpException;
    // }

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
}

