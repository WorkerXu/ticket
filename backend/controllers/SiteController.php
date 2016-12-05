<?php
namespace backend\controllers;

use common\models\OddSame;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use common\models\Odds;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Match;

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
                        'actions' => ['error', 'odd', 'match', 'store', 'mysql-odd', 'similar', 'add-rank'],
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

    public function actionMatch()
    {
        $list = Odds::getData(Odds::matchList(Yii::$app->request->get('day', 0)));
        if(isset($list->list))
        {
            $list = Odds::dealMatch($list->list, Yii::$app->request->queryParams);
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

    public function actionStore()
    {
        $match = Odds::getMatch(Yii::$app->request->get('data'));

        if(!empty($match))
        {
            Odds::store($match);
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
        ini_set('max_execution_time', '0');

        $begin  = "";
        $end    = "";

        $list = Odds::getData(Odds::matchList(Yii::$app->request->get('day', 1)));
        if(isset($list->list))
        {
            $list = Odds::dealMatch($list->list, ['type' => 2]);
        }
//        var_dump($list);exit();
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


}
