<?php

namespace app\controllers;

use Yii;
use app\models\Books;
use app\models\BooksSearch;
use app\models\Owners;
use app\models\Authors;
use app\models\BookAuthor;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use Da\QrCode\QrCode;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    // public function beforeAction($action)
    // {
    //     if (in_array($action->id, ['create', 'update', 'view']) && !Yii::$app->user->can('manageBook', ['book' => $book])) {
    //         throw new ForbiddenHttpException;
    //     } else {
    //         return parent::beforeAction($action);
    //     }
    // }

    /**
     * Lists all Books models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BooksSearch();

        if(Yii::$app->user->can('checkOwnList')){
            $searchModel->owner_id = Yii::$app->user->identity->owner_id;
        } else {
            if(!Yii::$app->user->can('manage')){
                throw new ForbiddenHttpException;
            }
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Books model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $book = $this->findModel($id);

        if(!Yii::$app->user->can('checkOwnList') && !Yii::$app->user->can('manage')){
            throw new ForbiddenHttpException;
        }

        $qr_code = (new QrCode(utf8_encode($book->qr_code)))
            ->setSize(200)
            ->setMargin(5)
            ->useForegroundColor(51, 153, 255);

        $q = '<img src="' . $qr_code->writeDataUri() . '">';

        return $this->render('view', [
            'model' => $this->findModel($id),
            'q' => $q,
        ]);
    }

    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Books();

        if(Yii::$app->user->can('checkOwnList')){
            $model->owner_id = Yii::$app->user->identity->owner_id;
        } else {
            if(!Yii::$app->user->can('manage')){
                throw new ForbiddenHttpException;
            }
        }

        $owners = Owners::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'owners' => $owners
            ]);
        }
        /*
        if ($model->load(Yii::$app->request->post())) {
            //TO DO
            if (something && $model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        }*/

        /*return $this->render('create', [
            'model' => $model,
        ]);*/
    }

    /**
     * Updates an existing Books model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)///////////////////////////проверить
    {
        $model = $this->findModel($id);

        if(!Yii::$app->user->can('manageBook', ['book' => $model])){
            throw new ForbiddenHttpException;
        }

        $owners = Owners::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'owners' => $owners,
            ]);
        }
    }

    /**
     * Deletes an existing Books model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if(!Yii::$app->user->can('manageBook', ['book' => $model])){
            throw new ForbiddenHttpException;
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
