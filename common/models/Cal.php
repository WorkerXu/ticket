<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\helpers\Curl;
use yii\helpers\ArrayHelper;

/**
 * Cal model
 *
 */

class Cal extends Model
{

    public static $BET_ODD;
    public static $YSB_ODD;
    public static $AOM_ODD;
    public static $ALL_SOC;
    public static $HOM_SOC;
    public static $AWY_SOC;

    /* <-- 处理数据结构部分BEGIN --> */

    /**
     * 获取data数据
     * @param $json
     * @return array
     */
    public static function getData($json)
    {
        $res = json_decode($json);
        return isset($res->data) ? $res->data : array();
    }

    /**
     * 处理比赛信息
     * @param $data
     * @return array
     */
    public static function getMatch($data)
    {
        $res = array();

        if(isset($data['fid']) || isset($data->fid))
        {
            $res['fid']    = ArrayHelper::getValue($data, 'fid');
            $res['mdate']  = ArrayHelper::getValue($data, 'vsdate');
            $res['hname']  = ArrayHelper::getValue($data, 'hname');
            $res['aname']  = ArrayHelper::getValue($data, 'aname');
            $res['league'] = ArrayHelper::getValue($data, 'lname');
            $res['score']  = ArrayHelper::getValue($data, 'hscore'). ":" .ArrayHelper::getValue($data, 'ascore');
        }

        return $res;
    }

    /**
     * 处理赔率
     * @param $odds
     * @param $tag
     * @return array
     */
    public static function getOdd($odds, $tag)
    {
        $i = 0;
        $res = array();

        foreach ($odds as $odd)
        {
            if(isset($odd->time)){
                $res[$i]['tag'] = $tag;
                $res[$i]['time'] = $odd->time;
                $res[$i]['home'] = $odd->home;
                $res[$i]['away'] = $odd->away;
                $res[$i]['odd']  = $odd->handi;
                $res[$i]['home_text'] = $odd->s1 == 1 ? 'text-danger' : 'text-info';
                $res[$i]['away_text'] = $odd->s2 == 1 ? 'text-danger' : 'text-info';

            }
            $i++;
        }

        return $res;
    }

    /**
     * 处理赛事数据
     * @param $rank
     * @param string $tag
     * @return mixed
     */
    public static function getOneStep($rank, $tag, $type)
    {
        $ctag  = $tag .'standing';
        $ctype = $type . 'win';
        if(isset($rank->ranks))
        {
            $ranks = $rank->ranks;
            if (isset($ranks[0]->$ctag->$ctype))
            {
                $res['win']  = ArrayHelper::getValue($rank, 'ranks.0.'. $tag .'standing.'. $type .'win',  '0');
                $res['draw'] = ArrayHelper::getValue($rank, 'ranks.0.'. $tag .'standing.'. $type .'draw', '0');
                $res['lost'] = ArrayHelper::getValue($rank, 'ranks.0.'. $tag .'standing.'. $type .'lost', '0');
            }
        }
        $res['tag']  = $type.$tag.'standing';
        return $res;
    }

    public static function getTwoStep($rank, $tag)
    {
        $res['lost'] = ArrayHelper::getValue($rank, $tag.'_datatotal.lost', '0');
        $res['draw'] = ArrayHelper::getValue($rank, $tag.'_datatotal.draw', '0');
        $res['win']  = ArrayHelper::getValue($rank, $tag.'_datatotal.win',  '0');
        $res['tag']  = $tag.'_datatotal';

        return $res;
    }

    public static function getThrStep($rank, $tag)
    {
        $res['win']  = ArrayHelper::getValue($rank, 'win',  '0');
        $res['draw'] = ArrayHelper::getValue($rank, 'draw', '0');
        $res['lost'] = ArrayHelper::getValue($rank, 'lost', '0');
        $res['tag']  = $tag;

        return $res;
    }

    /* <-- 处理数据结构部分END --> */

    /* <-- curl获取数据部分BEGIN --> */

    /**
     * 获取比赛列表
     * @param null $day
     * @return bool
     */
    public static function matchList($day = null)
    {
        $post_data = [
            "mytime" => time()."000",
            "stid"   => 1,
            "t"      => 1,
            "c_key"  => "c28d797bdf3f789e759150cdac45957a",
            "c_ck"   => "MjM4OTUyNmJiZmFjZGQ4YWQ4ZjY0Njk1ZjIxMWU3MDhlNjc1NzU",
            "cid"    => 9,
            "c_id"   => 41000,
            "c_type" => 2,
            "c_cpid" => 2,
            "suid"	 => "51135b8f9224fabfe13e8ff68d18729c",
            "quid"   => 238952
        ];

        if($day)
        {
            $post_data['t']   = 2;
            $post_data['day'] = intval($day);
        }

        try{
            $curl = new Curl("i.qqshidao.com", "/api/index.php", "POST", 80, true);
            $curl->setData($post_data);
            return $curl->execute()->getResponseText();
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * 获取赛事赔率
     * @param $fid
     * @param $vsdate
     * @param int $lji
     * @return bool
     * 9 易胜博
     * 502 12BET
     * 5 澳门
     */
    public static function matchOdd($fid, $vsdate, $bet = 502, $ysb = 9, $aom = 5)
    {
        $post_data = [
            "vsdate" => $vsdate,
            "fid"    => $fid,
            "t"      => 2,
            "c_key"  => "efff0a84f860ff38fe8f5abfa0a68496",
            "c_id"   => 40020,
            "c_type" => 2,
            "c_cpid" => 2,
            "suid"	 => "51135b8f9224fabfe13e8ff68d18729c",
        ];

        try{
            $curl = new Curl("i.qqshidao.com", "/api/index.php", "POST", 80, true);
            //bet赔率
            $post_data['cid'] = $bet;
            $curl->setData($post_data);
            self::$BET_ODD = self::isJson($curl->execute()->getResponseText());
            //易胜博赔率
            $post_data['cid'] = $ysb;
            $curl->setData($post_data);
            self::$YSB_ODD = self::isJson($curl->execute()->getResponseText());
            //澳门赔率
            $post_data['cid'] = $aom;
            $curl->setData($post_data);
            self::$AOM_ODD = self::isJson($curl->execute()->getResponseText());
            //end
            $curl->close();
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * 获取赛事数据
     * @param $fid
     * @return bool
     */
    public static function matchRank($fid)
    {
        $post_data = [
            "fid"    => $fid,
            "c_key"  => "8a7f6b611dd399c8cafcc46c35b9853c",
            "quid"   => 238952,
            "c_id"   => 40006,
            "c_type" => 2,
            "c_cpid" => 2,
            "suid"	 => "51135b8f9224fabfe13e8ff68d18729c",
        ];

        try{
            $curl = new Curl("i.qqshidao.com", "/api/index.php", "POST", 80, true);

            //获取id
            $cofo = $post_data;
            $cofo['c_ck'] = 'MjM4OTUyN2NjMDJkOTE4NTE3N2RkNWEyZDAxMTdlZDhkMGRhYmE=';
            $curl->setData($cofo);
            $hid = self::getData($curl->execute()->getResponseText())->hid;
            $aid = self::getData($curl->execute()->getResponseText())->aid;

            //获取第一页
            $coft = $post_data;
            $coft['c_id']   = 41101;
            $coft['c_key'] = '541ba457d67da316c6acbbd1e57004f5';
            $curl->setData($coft);
            self::$ALL_SOC = self::isJson($curl->execute()->getResponseText());

            //获取第二页
            $cofr = $post_data;
            $cofr['c_id']    = 40026;
            $cofr['id']      = $hid;
            $cofr['t']       = 1;
            $cofr['ishome']  = 1;
            $cofr['c_key']   = '5e54b08fc6203e194a1b837cc5bc15a0';
            $curl->setData($cofr);
            self::$HOM_SOC = self::isJson($curl->execute()->getResponseText());

            //获取第三页
            $coff = $post_data;
            $coff['c_id']    = 40026;
            $coff['id']      = $aid;
            $coff['t']       = 2;
            $coff['ishome']  = 0;
            $coff['c_key']   = '5e54b08fc6203e194a1b837cc5bc15a0';
            $curl->setData($coff);
            self::$AWY_SOC = self::isJson($curl->execute()->getResponseText());

            //结束
            $curl->close();
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /* <-- curl获取数据部分END --> */

    /* <-- 赛事数据展示部分BEGIN --> */

    /**
     * 筛选比赛类型
     * @param $list
     * @param $params
     * @return mixed
     */
    public static function dealMatch($list, $params)
    {
        if(!empty($list))
        {
            if (isset($params['type'])){
                foreach ($list as $key => $match)
                {
                    if($params['type'] == 2)
                    {
                        break;
                    }
                    elseif ($match->isjczq != $params['type'])
                    {
                        unset($list[$key]);
                    }
                }
            }
        }
        return $list;
    }

    /* <-- 赛事数据展示部分END --> */

    /* <-- 赛事数据主要部分BEGIN --> */

    /**
     * 赔率排序
     * @param $bet_odd
     * @param $lji_odd
     * @return array
     */
    public static function orderOdd($bet_odd, $lji_odd)
    {
        $i = 0;
        $j = 0;
        $count = count($lji_odd);
        $res = array();

        foreach ($bet_odd as $k=> $value)
        {
            if (isset($lji_odd[$j]) && strtotime($value['time']) <= strtotime($lji_odd[$j]['time']))
            {
                for ($j; $j <= $count; $j++)
                {
                    if (isset($lji_odd[$j]) && strtotime($value['time']) < strtotime($lji_odd[$j]['time']))
                    {
                        $res[$i]['time'] = $lji_odd[$j]['time'];
                        $res[$i][$lji_odd[$j]['tag']] = $lji_odd[$j];
                    }
                    elseif (isset($lji_odd[$j]) && strtotime($value['time']) == strtotime($lji_odd[$j]['time']))
                    {
                        $res[$i]['time'] = $value['time'];
                        $res[$i][$lji_odd[$j]['tag']] = $lji_odd[$j];
                        if(isset($value['tag']))
                        {
                            $res[$i][$value['tag']] = $value;
                        }
                        else
                        {
                            $res[$i] = $res[$i] + $value;
                        }
                        $j++;
                        break;
                    }
                    else
                    {
                        $res[$i]['time'] = $value['time'];
                        if(isset($value['tag']))
                        {
                            $res[$i][$value['tag']] = $value;
                        }
                        else
                        {
                            $res[$i] = $value;
                        }
                        break;
                    }

                    $i++;
                }
            }
            else
            {
                $res[$i]['time'] = $value['time'];
                if(isset($value['tag']))
                {
                    $res[$i][$value['tag']] = $value;
                }
                else
                {
                    $res[$i] = $value;
                }
            }
            $i++;
        }

        if($j < $count)
        {
            for ($key = $j; $key < $count; $key++)
            {
                $res[$i]['time'] = $lji_odd[$key]['time'];
                $res[$i][$lji_odd[$key]['tag']] = $lji_odd[$key];
                $i++;
            }
        }

        return $res;
    }

    /**
     * @param $target
     * @param $sames
     * @return mixed
     */
    public static function sameOdd($target, $sames, $tag)
    {
        $target = $target->$tag;
        $home   = $target->home;
        $away   = $target->away;
        $handi  = $target->handi;

        if ($away != '0'){
            foreach($sames as $key => $same)
            {
                $same = $same->$tag;
                if ($same->home == '0' || $same->away == '0')
                {
                    unset($sames[$key]);
                    continue;
                }
                if(abs(number_format(floatval($same->home) / floatval($same->away), 3) - number_format(floatval($home) / floatval($away), 3)) <= self::getByte($home, $away) && $same->handi == $handi)
                {
                    continue;
                }
                elseif (abs(number_format(floatval($same->away) / floatval($same->home), 3) - number_format(floatval($home) / floatval($away), 3)) <= self::getByte($home, $away) && str_replace('-', '', $same->handi) == str_replace('-', '', $handi) && (self::compare($home, $away) === self::compare($same->away, $same->home)) && $same->handi !== $handi)
                {
                    continue;
                }else{
                    unset($sames[$key]);
                }
            }
        }
        return $sames;
    }

    /**
     * 存储比赛结果集
     * @param $match
     */
    public static function store($match)
    {
        ini_set ('memory_limit', '512M');
        try
        {
            $transaction = Yii::$app->getDb()->beginTransaction();

            $exist = Matchs::findOne(['fid' => $match['fid']]);

            if(!is_null($exist))
            {
                if(!$exist->delete())
                {
                    throw new \Exception('match fail delete');
                }
            }

            $model = new Matchs();
            $model->isNewRecord == true;

            $model->setAttributes($match);

            if($model->save())
            {
                self::matchOdd($model->fid, $model->mdate);

                //插入bet赔率
                $bet_odd = self::getData(self::$BET_ODD);
                $bet_odd = self::getOdd($bet_odd, "bet");

                foreach($bet_odd as $bet)
                {
                    $odd = new Odds();
                    $odd->setAttributes($bet);
                    $odd->match_id = $model->id;
                    if(!$odd->save()){
                        throw new \Exception('fail insert bet odd');
                    }
                }

                //插入易胜博赔率
                $ysb_odd = self::getData(self::$YSB_ODD);
                $ysb_odd = self::getOdd($ysb_odd, "ysb");

                foreach($ysb_odd as $ysb)
                {
                    $odd = new Odds();
                    $odd->setAttributes($ysb);
                    $odd->match_id = $model->id;
                    if(!$odd->save()){
                        throw new \Exception('fail insert ysb odd');
                    }
                }

                //插入澳门赔率
                $aom_odd = self::getData(self::$AOM_ODD);
                $aom_odd = self::getOdd($aom_odd, "aom");

                foreach($aom_odd as $aom)
                {
                    $odd = new Odds();
                    $odd->setAttributes($aom);
                    $odd->match_id = $model->id;
                    if(!$odd->save()){
                        throw new \Exception('fail insert aom odd');
                    }
                }

                //插入sim赔率
                $tags = [
                    'bet',
                    'ysb',
                    'aom',
                    'ays',
                    'ybs',
                ];
                foreach ($tags as $tag)
                {
                    $same = new Sames();
                    $odd  = $model->$tag();

                    $same->home  = isset($odd['home']) ? $odd['home'] : '0';
                    $same->away  = isset($odd['away']) ? $odd['away'] : '0';
                    $same->handi = isset($odd['odd'])  ? $odd['odd']  : '0';
                    $same->tag   = $tag;
                    $same->match_id = $model->id;

                    if(!$same->save())
                    {
                        throw new \Exception('fail insert same '. $tag .' odd');
                    }

                }

                //插入赛事数据
                self::matchRank($model->fid);
                //主客场
                $map  = [
                    [
                        'tag' => 'home',
                        'type' => '',
                    ],
                    [
                        'tag' => 'home',
                        'type' => 'c',
                    ],
                    [
                        'tag' => 'away',
                        'type' => '',
                    ],
                    [
                        'tag' => 'away',
                        'type' => 'c',
                    ],
                ];
                foreach ($map as $m)
                {
                    $score = new Scores();
                    $rank  = self::getOneStep(self::getData(self::$ALL_SOC), $m['tag'], $m['type']);
                    $score->match_id = $model->id;
                    $score->setAttributes($rank);

                    if(!$score->save())
                    {
                        throw new \Exception(json_encode($score->getErrors()));
                    }
                }
                unset($m);

                //近十场和对阵
                $map  = [
                    'home',
                    'away',
                    'fuck',
                ];
                foreach ($map as $m)
                {
                    $score = new Scores();
                    $rank  = self::getTwoStep(self::getData(self::$ALL_SOC), $m);
                    $score->match_id = $model->id;
                    $score->setAttributes($rank);

                    if(!$score->save())
                    {
                        throw new \Exception(json_encode($score->getErrors()));
                    }
                }
                unset($m);

                //主客近十场
                $map  = [
                    [
                        'tag' => 'homes',
                        'val' => self::$HOM_SOC,
                    ],
                    [
                        'tag' => 'aways',
                        'val' => self::$AWY_SOC,
                    ],
                ];
                foreach ($map as $m)
                {
                    $score = new Scores();
                    $rank  = self::getThrStep(self::getData($m['val']), $m['tag']);
                    $score->match_id = $model->id;
                    $score->setAttributes($rank);

                    if(!$score->save())
                    {
                        throw new \Exception(json_encode($score->getErrors()));
                    }
                }

                $transaction->commit();
            }
        }catch (\Exception $e){

            $transaction->rollBack();
            var_dump($e->getMessage());exit();
        }
    }

    /* <-- 辅助处理方法BEGIN --> */

    /**
     * 删除时间，赔率排序用
     * @param $odds
     * @return mixed
     */
    public static function unsetTime($odds)
    {
        foreach ($odds as $key => $odd)
        {
            if(isset($odd['time']))
            {
                unset($odds[$key]['time']);
            }
        }
        return $odds;
    }

    /**
     * 检验是否为json数据
     * @param $string
     * @return string
     */
    private static function isJson($string)
    {
        if(!is_string($string))
        {
            return '{}';
        }
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) ? $string : '{}';
    }

    /**
     * 获取比率
     * @param $home
     * @param $away
     * @return float
     */
    private static function getByte($home, $away)
    {
        return floatval($home) > floatval($away) ? 0.085 : 0.060;
    }

    /**
     * 比较逻辑
     * @param $home
     * @param $away
     * @return bool
     */
    private static function compare($home, $away)
    {
        return floatval($home) > floatval($away) ? true : false;
    }

    /* <-- 辅助处理方法END --> */
}

