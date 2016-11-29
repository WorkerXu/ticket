<?php
namespace backend\controllers;

use common\models\Detail;
use common\models\Odd;
use common\models\OddSame;
use common\models\Power;
use common\models\Rank;
use common\models\Total;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
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
                        'actions' => ['login', 'error', 'odd', 'match', 'store', 'mysql-odd', 'similar', 'add-rank'],
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
        ini_set ('memory_limit', '512M');
        try
        {
            $transaction = Yii::$app->getDb()->beginTransaction();

            $exist = Match::findOne(['fid' => Yii::$app->request->get('fid')]);

            if(!is_null($exist))
            {
                if(!$exist->delete())
                {
                    throw new \Exception('match fail delete');
                }
            }

            $model = new Match();
            $model->isNewRecord == true;

            $model->fid   = Yii::$app->request->get('fid');
            $model->hname = Yii::$app->request->get('hname');
            $model->aname = Yii::$app->request->get('aname');
            $model->score = Yii::$app->request->get('hscore'). ":" .Yii::$app->request->get('ascore');
            $model->mdate = Yii::$app->request->get('mdate');

            if($model->save())
            {
                Odds::matchOdd($model->fid, $model->mdate);

                $bet_odd = Odds::getData(Odds::getBetOdd());
                $bet_odd = Odds::getOdd($bet_odd, "bet");

                foreach($bet_odd as $bet)
                {
                    $odd = new Odd();
                    $odd->setAttributes($bet);
                    $odd->match_id = $model->id;
                    if(!$odd->save()){
                        throw new \Exception('bet fail insert odd');
                    }
                }

                $lji_odd = Odds::getData(Odds::getLijiOdd());
                $lji_odd = Odds::getOdd($lji_odd, "lji");

                foreach($lji_odd as $lji)
                {
                    $odd = new Odd();
                    $odd->setAttributes($lji);
                    $odd->match_id = $model->id;
                    if(!$odd->save()){
                        throw new \Exception('lji fail insert odd');
                    }
                }

                $same = new OddSame();
                $same->b_home = isset($model->getBetFirstOdd()['home']) ? $model->getBetFirstOdd()['home'] : '';
                $same->b_away = isset($model->getBetFirstOdd()['away']) ? $model->getBetFirstOdd()['away'] : '';
                $same->b_handi = isset($model->getBetFirstOdd()['odd']) ? $model->getBetFirstOdd()['odd'] : '';
                $same->l_away = isset($model->getLjiFirstOdd()['away']) ? $model->getLjiFirstOdd()['away'] : '';
                $same->l_home = isset($model->getLjiFirstOdd()['home']) ? $model->getLjiFirstOdd()['home'] : '';
                $same->l_handi = isset($model->getLjiFirstOdd()['odd']) ? $model->getLjiFirstOdd()['odd'] : '';
                $same->s_away = isset($model->getBetSameLjiOdd()['away']) ? $model->getBetSameLjiOdd()['away'] : '';
                $same->s_handi = isset($model->getBetSameLjiOdd()['odd']) ? $model->getBetSameLjiOdd()['odd'] : '';
                $same->s_home = isset($model->getBetSameLjiOdd()['home']) ? $model->getBetSameLjiOdd()['home'] : '';
                $same->match_id = $model->id;

                if(!$same->save())
                {
                    throw new \Exception('same fail insert odd');
                }

                $transaction->commit();

                try{
                    Odds::matchRank($model->fid);

                    $ranks = Odds::getData(Odds::getMatchRank());
                    $home_rank = Odds::getRank($ranks, "home");
                    $rank = new Rank();
                    $rank->match_id = $model->id;
                    $rank->setAttributes($home_rank);
                    $rank->save();

                    $away_rank = Odds::getRank($ranks, "away");
                    $rank = new Rank();
                    $rank->match_id = $model->id;
                    $rank->setAttributes($away_rank);
                    $rank->save();
                }catch (\Exception $e) {
                    //...
                }

                try{
                    Odds::matchRank($model->fid);
                    $ranks = Odds::getData(Odds::getMatchRank());

                    $fucks = Odds::getDetail($ranks, 'fuck');

                    foreach ($fucks as $fuck)
                    {
                        $detail = new Detail();
                        $detail->match_id = $model->id;
                        $detail->setAttributes($fuck);
                        $detail->save();
                    }

                    $homes = Odds::getDetail($ranks, 'home');

                    foreach ($homes as $home)
                    {
                        $detail = new Detail();
                        $detail->match_id = $model->id;
                        $detail->setAttributes($home);
                        $detail->save();
                    }

                    $aways = Odds::getDetail($ranks, 'away');

                    foreach ($aways as $away)
                    {
                        $detail = new Detail();
                        $detail->match_id = $model->id;
                        $detail->setAttributes($away);
                        $detail->save();
                    }
                }catch (\Exception $e)
                {
                    //...
                }

                try{
                    Odds::matchRank($model->fid);
                    $ranks = Odds::getData(Odds::getMatchRank());

                    $fuck = Odds::getTotal($ranks, 'fuck');
                    $total = new Total();
                    $total->match_id = $model->id;
                    $total->setAttributes($fuck);
                    $total->save();

                    $home = Odds::getTotal($ranks, 'home');
                    $total = new Total();
                    $total->match_id = $model->id;
                    $total->setAttributes($home);
                    $total->save();

                    $away = Odds::getTotal($ranks, 'away');
                    $total = new Total();
                    $total->match_id = $model->id;
                    $total->setAttributes($away);
                    $total->save();
                }catch (\Exception $e)
                {
                    //...
                }

                try{
                    Odds::matchRank($model->fid);
                    $ranks = Odds::getData(Odds::getMatchRank());

                    $home = Odds::getPower($ranks, 'h');
                    $power = new Power();
                    $power->match_id = $model->id;
                    $power->setAttributes($home);
                    $power->save();

                    $away = Odds::getPower($ranks, 'a');
                    $power = new Power();
                    $power->match_id = $model->id;
                    $power->setAttributes($away);
                    $power->save();
                }catch (\Exception $e)
                {
                    //...
                }
            }
        }catch (\Exception $e){

            $transaction->rollBack();
            var_dump($e->getMessage());exit();
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

        foreach (Match::find()->all() as $match)
        {
            //....
        }
    }


}
