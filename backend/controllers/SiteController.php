<?php
namespace backend\controllers;

use common\models\Cal;
use common\models\Matchs;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use common\models\Odds;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Match;

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
     * 比赛赔率页
     * @return string
     */
    public function actionOdd()
    {
        $bet_odd = $lji_odd = array();
        $fid = Yii::$app->request->get('fid', 0);

        if($match = Matchs::findOne(['fid' => $fid]))
        {
            $bet_odd = $match->betOdds();
            $ysb_odd = $match->ysbOdds();
            $aom_odd = $match->aomOdds();
        }

        $odds = Cal::orderOdd($bet_odd, $ysb_odd);
        $odds = Cal::orderOdd($odds, $aom_odd);
        $odds = Cal::unsetTime($odds);

        $provider = new ArrayDataProvider([
            'allModels' => $match->scores,
        ]);
        return $this->render('odd',
            [
                'odds'     => $odds,
                'provider' => $provider,
            ]
        );
    }

    /**
     * 查询相似比赛
     * @return string
     */
    public function actionSimilar()
    {
        $target = Matchs::findOne(['fid' => Yii::$app->request->get('fid', 0)]);
        $sames  = Matchs::find()->orderBy(['mdate' => SORT_DESC])->all();

        $sames  = Cal::sameOdd($target, $sames, 'Bet');
        $sames  = Cal::sameOdd($target, $sames, 'Ysb');
        $sames  = Cal::sameOdd($target, $sames, 'Aom');
        $sames  = Cal::sameOdd($target, $sames, 'Ays');
        $sames  = Cal::sameOdd($target, $sames, 'Ybs');

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
        $matchs = Match::find()->andWhere(['<=', 'mdate', '2016-12-14 02:30:00'])->orderBy(['mdate' => SORT_DESC])->all();

        while (!empty($matchs)){
            $m = array_splice($matchs, 0, 100);
            for ($i = 0; $i < count($m); $i++)
            {
                $data = $m[$i]->attributes;
                if (!empty($data))
                {
                    unset($data['id']);
                    Cal::store($data);
                }
                unset($data);
            }
            unset($m);
        }
        unset($matchs);
    }
}
