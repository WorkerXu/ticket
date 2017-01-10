<?php
namespace backend\controllers;

use common\helpers\Curl;
use common\models\Cal;
use common\models\Odd;
use common\models\OddSame;
use common\models\Socre;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use common\models\Odds;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Match;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $defaultAction = "match";

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
                        'actions' => ['error', 'odd', 'match', 'store', 'mysql-odd', 'similar', 'add-rank', 'tmp', 'add-socre', 'cal-socre'],
                        'allow' => true,
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

    public function actionOdd()
    {
        $fid = Yii::$app->request->get('fid', 0);
        $date = Yii::$app->request->get('date', date("Y-m-d H:i:s"));
        Odds::matchOdd($fid, $date);

        $lji_odd = Odds::getData(Odds::getLijiOdd());
        $lji_odd = Odds::getOdd($lji_odd, "lji");

        $bet_odd = Odds::getData(Odds::getBetOdd());
        $bet_odd = Odds::getOdd($bet_odd, "bet");

        $odds = Odds::orderOdd($bet_odd, $lji_odd);
        $odds = Odds::unsetTime($odds);
        $odds = Odds::calDiff($odds);
        $odds = Odds::addDiffs($odds, "bet", "lji");
        $odds = Odds::addDiffs($odds, "lji", "bet");

        return $this->render('odd',
            [
                'odds' => $odds,
            ]
        );
    }

    public function actionMysqlOdd()
    {
        $bet_odd = $lji_odd = array();
        $fid = Yii::$app->request->get('fid', 0);

        if($match = Match::findOne(['fid' => $fid]))
        {
            $bet_odd = $match->betOdds;
            $lji_odd = $match->ljiOdds;
        }

        $odds = Odds::orderOdd($bet_odd, $lji_odd);
        $odds = Odds::unsetTime($odds);
        $odds = Odds::calDiff($odds);
        $odds = Odds::addDiffs($odds, "bet", "lji");
        $odds = Odds::addDiffs($odds, "lji", "bet");

        $rank_provider = new ArrayDataProvider([
            'allModels' => $match->ranks,
        ]);

        $fuck_provider = new ArrayDataProvider([
            'allModels' => $match->fuckDetails,
        ]);

        $home_provider = new ArrayDataProvider([
            'allModels' => $match->homeDetails,
        ]);

        $away_provider = new ArrayDataProvider([
            'allModels' => $match->awayDetails,
        ]);

        $ft_provider = new ArrayDataProvider([
            'allModels' => $match->fuckTotal,
        ]);

        $ht_provider = new ArrayDataProvider([
            'allModels' => $match->homeTotal,
        ]);

        $at_provider = new ArrayDataProvider([
            'allModels' => $match->awayTotal,
        ]);

        $power_provider = new ArrayDataProvider([
            'allModels' => $match->powers,
        ]);


        return $this->render('odd',
            [
                'odds' => $odds,
                'rank_provider'    => $rank_provider,
                'fuck_provider'    => $fuck_provider,
                'home_provider'    => $home_provider,
                'away_provider'    => $away_provider,
                'ft_provider'      => $ft_provider,
                'ht_provider'      => $ht_provider,
                'at_provider'      => $at_provider,
                'power_provider'   => $power_provider,
            ]
        );
    }

    /**
     * 比赛主页
     * @return string
     */
    public function actionMatch()
    {
        $list = Cal::getData(Cal::matchList(Yii::$app->request->get('day', 0)));
        if(isset($list->list))
        {
            $list = Cal::dealMatch($list->list, Yii::$app->request->queryParams);
        }
        $provider = new ArrayDataProvider([
            'allModels' => $list,
            'pagination' => [
                'pageSize' => 10,
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

    /**
     * 存储比赛数据
     */
    public function actionStore()
    {
        $match = Cal::getMatch(Yii::$app->request->get('data'));
        if(!empty($match))
        {
            Cal::store($match);
        }

        $this->redirect("match");
    }

    public function actionSimilar()
    {
        $target = Match::findOne(['fid' => Yii::$app->request->get('fid', 0)]);
        $sames  = OddSame::find()->innerJoin('match', 'match.id = match_id')->orderBy(['mdate' => SORT_DESC])->all();
        $sames  = Odds::sameBet($target->oddSame, $sames);
        $sames  = Odds::sameLji($target->oddSame, $sames);
        $sames  = Odds::sameSim($target->oddSame, $sames);
        $sames  = Odds::sameSocre($target, $sames);

        $provider = new ArrayDataProvider([
            'allModels' => $sames,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render("similar",[
            "provider" => $provider,
        ]);
    }

    public function actionAddRank()
    {
        ini_set ('memory_limit', '512M');
        ini_set ('max_execution_time', '0');

        $list = Odds::getData(Odds::matchList(Yii::$app->request->get('day', 1)));
        if(isset($list->list))
        {
            $list = Odds::dealMatch($list->list, ['type' => 2]);
        }
        foreach ($list as $match)
        {
            $data = Odds::getMatchObj($match);

            if(!empty($data))
            {
                $new = is_null(Match::findOne(['fid' => $match->fid]));
                if ($new)
                {
                    Odds::store($data);
                }
            }
        }
    }

    //刷数据专用
    public function actionTmp()
    {
        ini_set ('memory_limit', '512M');
        ini_set ('max_execution_time', '0');
        $matchs = Match::find()->andWhere(['<=', 'mdate', '2016-11-22 16:29:00'])->orderBy(['mdate' => SORT_DESC])->all();

        while (!empty($matchs)){
            $m = array_splice($matchs, 0, 100);
            for ($i = 0; $i < count($m); $i++)
            {
                $data = $m[$i]->attributes;
                if (!empty($data))
                {
                    unset($data['id']);
                    Odds::store($data);
                }
                unset($data);
            }
            unset($m);
        }
        unset($matchs);
    }
}
