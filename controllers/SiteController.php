<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\SignupForm;
use app\models\User;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Owners;
use app\models\Books;
use app\models\BooksSearch;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;

class SiteController extends Controller
{
    // public function actionSearch() {
    //     $q = trim(Yii::$app->request->get('q'));
    //     if(!$q) return $this->render('search');
    //     $query = Books::find()->where(['like', 'title', $q]);
    //     $pages = new Pagination([
    //         'totalCount' => $query->count(),
    //         'pageSize' => 1,
    //         'forcePageParam' => false,
    //         'pageSizeParam' => false
    //     ]);
    //     $books = $query->orderBy('id DESC')
    //         ->offset($pages->offset)
    //         ->limit($pages->limit)
    //         ->all();
    //     return $this->render('search', compact('books','pages','q'));
    // }

    public function actionMainBookSearch($q = null) {
        $books = Books::find()->where(['like', 'title', $q])->orWhere(['like', 'description', $q])->all();
        //
        foreach ($books as $book) {
            $out[] = [
                'title' => $book->title,
                'value' => $book->title,
                'language' => 'lang',
                'description' => $book->description,
            ];
        }
        echo Json::encode($out);
    }

    public function actionGetSomeTags($tag){
        Yii::$app->response->format = RESPONSE::FORMAT_JSON;
        $p = \app\models\Tags::find()->where(['LIKE', 'title', $tag])->asArray()->all();
        $res1 = array();
        foreach($p as $val){
            $res1[] = ['name' => $val['title']];
        }
        //надо отдавать JSON, лучше почитать API в Yii2
        return $res1;
    }

    public function actionGetSomeAuthors($author){
        Yii::$app->response->format = RESPONSE::FORMAT_JSON;
        $p = \app\models\Authors::find()->where(['LIKE', 'title', $author])->asArray()->all();
        $res1 = array();
        foreach($p as $val){
            $res1[] = ['name' => $val['title']];
        }
        //надо отдавать JSON, лучше почитать API в Yii2
        return $res1;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSignup(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new SignupForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user->username = $model->username;
            $user->password = \Yii::$app->security->generatePasswordHash($model->password);
            if($user->save()) {
                return $this->goHome();
            }
        }
        return $this->render('signup', compact('model'));
       }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSearchBook()
    {
        $owners = Owners::find()->all();
        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('search-book', [
            'dataProvider' => $dataProvider,
            'owners' => $owners,
            'model' => $searchModel,
        ]);
    }
}
