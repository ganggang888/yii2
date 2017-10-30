<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\TidyStationEmp;
class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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
        $redis = Yii::$app->redis;
        $key = "tidy_plant";
        if ($redis->get($key)) {
            $data = unserialize($redis->get($key));
        } else {
            $data = Yii::$app->db->createCommand("SELECT * FROM plant_xiaoge_ticheng ")->queryAll();
            $redis->set($key, serialize($data));
            $redis->expire($key,30);
        }
        echo json_encode($data);die;
        return $this->render('index');
    }

    public function actionTest()
    {
        Yii::$app->redis->hmset('mioji','name','syc','age','24',100);
        var_dump(Yii::$app->redis->hgetall('mioji'));
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

    public function actionTrys()
    {
        $userInfo = new TidyStationEmp();
        $userInfo = $userInfo->find()->select(['tu.user_id','tu.nickname'])
            ->from("tidy_station_emp tse")
            ->join('left join', 'tidy_user tu', "tu.user_id=tse.user_id")
            ->where(["tse.station_id" => 72])
            ->asArray()
            ->all();
            var_dump($userInfo);
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

    public function actionSay($message = 'hello')
    {
        echo Yii::$app->request->getUserIP();
        return $this->render('say',['message'=>$message]);
    }
}
