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
        Odds::matchOdd($fid, $date, 9, 502);

//        $bet_odd = Odds::getData(Odds::getBetOdd());
//        $bet_odd = Odds::getOdd($bet_odd, "bet");

        $lji_odd = Odds::getData(Odds::getLijiOdd());
        $lji_odd = Odds::getOdd($lji_odd, "lji");

        $aom_odd = Odds::getData(Odds::getAOMENOdd());
        $aom_odd = Odds::getOdd($aom_odd, "aom");

        $odds = Odds::orderOdd($aom_odd, $lji_odd);
//        $odds = Odds::orderOdd($odds, $aom_odd);
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

    //刷数据专用
    public function actionTmp()
    {
        ini_set ('memory_limit', '512M');
        ini_set ('max_execution_time', '0');
        $matchs = Match::find()->andWhere(['<', 'mdate', '2016-11-22 16:30:00'])->orderBy(['mdate' => SORT_DESC])->all();

        foreach ($matchs as $match)
        {
            $data = $match->attributes;
            if (!empty($data))
            {
                unset($data['id']);
                Odds::store($data);
            }
        }
    }

    public function actionAddSocre($id)
    {
        $model = Match::findOne(['id' => $id]);
        if(is_null($model))
        {
            throw new NotFoundHttpException('比赛不存在');
        }
        $socre = is_null($model->socre) ? new Socre() : $model->socre;
        $socre->match_id = $id;
        if ($socre->load(Yii::$app->request->post()) && $socre->save())
        {
            return $this->redirect('match');
        }else {
            return $this->render('add-socre', [
                'model' => $socre,
                'id' => $id,
            ]);
        }
    }

    public function actionCalSocre($id)
    {
        $model = Match::findOne(['id' => $id]);
        if(is_null($model))
        {
            throw new NotFoundHttpException('比赛不存在');
        }
        $socre = is_null($model->socre) ? new Socre() : $model->socre;
        $cal = new Cal();

        if($cal->load(Yii::$app->request->post()))
        {
            $socre->hsocre = Odds::calSocre($cal->hscore, $cal->ascore, $cal->hsum);
            $socre->asocre = Odds::calSocre($cal->hasocre, $cal->aasocre, $cal->asum);
            $socre->ihsocre = Odds::calSocre(3*$cal->hmatchw, (1*$cal->hmatchd + 3*$cal->hmatchl), ($cal->hmatchw + $cal->hmatchd + $cal->hmatchl));
            $socre->iasocre = Odds::calSocre(3*$cal->hamatchw, (1*$cal->hamatchd + 3*$cal->hamatchl), ($cal->hamatchw + $cal->hamatchd + $cal->hamatchl));
            $socre->hmatch = Odds::calSocre(3*$cal->hmatchw, (1*$cal->amatchd + 3*$cal->amatchw), ($cal->hmatchw + $cal->hmatchd + $cal->hmatchl));
            $socre->amatch = Odds::calSocre(3*$cal->hamatchw, (1*$cal->aamatchd + 3*$cal->aamatchw), ($cal->hamatchw + $cal->hamatchd + $cal->hamatchl));
            $socre->fmatch = Odds::calSocre(3*$cal->fw, (1*$cal->fd + 3*$cal->fl), ($cal->fw + $cal->fd + $cal->fl));
            $socre->famcth = Odds::calSocre(3*$cal->fhw, (1*$cal->fhd + 3*$cal->fhl), ($cal->fhw + $cal->fhd + $cal->fhl));
            $socre->ftmacth = Odds::calSocre(3*$cal->fsw, (1*$cal->fsd + 3*$cal->fsl), ($cal->fsw + $cal->fsd + $cal->fsl));
            $socre->shmatch = Odds::calSocre(3*$cal->hjw, (1*$cal->ajd + 3*$cal->ajw), ($cal->hjw + $cal->hjd + $cal->hjl));
            $socre->ishmatch = Odds::calSocre(3*$cal->hjw, (1*$cal->hjd + 3*$cal->hjl), ($cal->hjw + $cal->hjd + $cal->hjl));
            $socre->thmatch = Odds::calSocre(3*$cal->hjsw, (1*$cal->ajsd + 3*$cal->ajsw), ($cal->hjsw + $cal->hjsd + $cal->hjsl));
            $socre->ithmatch = Odds::calSocre(3*$cal->hjsw, (1*$cal->hjsd + 3*$cal->hjsl), ($cal->hjsw + $cal->hjsd + $cal->hjsl));
            $socre->samatch = Odds::calSocre(3*$cal->hajw, (1*$cal->aajd + 3*$cal->aajw), ($cal->hajw + $cal->hajd + $cal->hajl));
            $socre->isamatch = Odds::calSocre(3*$cal->hajw, (1*$cal->hajd + 3*$cal->hajl), ($cal->hajw + $cal->hajd + $cal->hajl));
            $socre->samatch = Odds::calSocre(3*$cal->hajw, (1*$cal->aajd + 3*$cal->aajw), ($cal->hajw + $cal->hajd + $cal->hajl));
            $socre->isamatch = Odds::calSocre(3*$cal->hajw, (1*$cal->hajd + 3*$cal->hajl), ($cal->hajw + $cal->hajd + $cal->hajl));
            $socre->tamatch = Odds::calSocre(3*$cal->hajsw, (1*$cal->aajsd + 3*$cal->aajsw), ($cal->hajsw + $cal->hajsd + $cal->hajsl));
            $socre->itamatch =  Odds::calSocre(3*$cal->hajsw, (1*$cal->hajsd + 3*$cal->hajsl), ($cal->hajsw + $cal->hajsd + $cal->hajsl));
            $socre->match_id = $id;

            $socre->save();
            return $this->redirect(['add-socre', 'id' => $id]);
        }else{
            return $this->render('cal-socre', ['model' => $cal]);
        }
    }
}
