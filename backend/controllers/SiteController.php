<?php
namespace backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use common\models\Odds;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
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
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'odd', 'match'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionOdd()
    {
        $fid = Yii::$app->request->get('fid', 0);
        $date = Yii::$app->request->get('date', date("Y-m-d H:i:s"));
        Odds::matchOdd($fid, $date);

        $bet_odd = Odds::getData(Odds::getBetOdd());
        $bet_odd = Odds::getOdd($bet_odd, "bet");

        $lji_odd = Odds::getData(Odds::getLijiOdd());
        $lji_odd = Odds::getOdd($lji_odd, "lji");

        $odds = Odds::orderOdd($bet_odd, $lji_odd);
        $odds = Odds::calDiff($odds);
        $odds = Odds::addDiffs($odds, "bet", "lji");
        $odds = Odds::addDiffs($odds, "lji", "bet");

        return $this->render('odd',
            [
                'odds' => $odds,
            ]
        );
    }

    public function actionMatch()
    {
        $list = Odds::getData(Odds::matchList(Yii::$app->request->get('day', 0)));
        $list = Odds::dealMatch($list->list, Yii::$app->request->queryParams);
        $provider = new ArrayDataProvider([
            'allModels' => $list,
            'pagination' => [
                'pageSize' => 1,
            ],
            'sort' => [
                'attributes' => [
                    'vsdate'
                ],
                'defaultOrder' => [
                    'vsdate' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render("match",[
            "provider" => $provider,
        ]);
    }
}
