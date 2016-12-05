<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\helpers\Curl;
use yii\helpers\ArrayHelper;

/**
 * Odds model
 *
 */

class Odds extends Model
{

    private static $BET_ODD;
    private static $LIJI_ODD;
    private static $MATCH_RANK;

    public static function getBetOdd()
    {
        return self::isJson(self::$BET_ODD) ? self::$BET_ODD : '{}';
    }

    public static function getLijiOdd()
    {
        return self::isJson(self::$LIJI_ODD) ? self::$LIJI_ODD : '{}';
    }

    public static function getMatchRank()
    {
        return self::isJson(self::$MATCH_RANK) ? self::$MATCH_RANK : '{}';
    }

    public static function getData($json)
    {
        $res = json_decode($json);
        return isset($res->data) ? $res->data : array();
    }

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

    public static function getRank($rank, $table = "home")
    {
        $res['name']      = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.name');
        $res['win']       = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.win');
        $res['draw']      = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.draw');
        $res['lost']      = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.lost');
        $res['score']     = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.score');
        $res['innum']     = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.innum');
        $res['lostnum']   = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.lostnum');
        $res['standing']  = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.standing');
        $res['cwin']      = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.cwin');
        $res['cdraw']     = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.cdraw');
        $res['clost']     = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.clost');
        $res['cscore']    = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.cscore');
        $res['cinnum']    = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.cinnum');
        $res['clostnum']  = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.clostnum');
        $res['cstanding'] = ArrayHelper::getValue($rank, 'ranks.0.'.$table.'standing.cstanding');
        $res['type']      = $table;

        return $res;
    }

    public static function getDetail($rank, $type = "fuck")
    {
        $i = 0;
        $res = array();
        $details = ArrayHelper::getValue($rank, $type."_datadetail", array());
        foreach ($details as $detail)
        {
            $res[$i]['fid']   = $detail->fid;
            $res[$i]['date']  = $detail->date;
            $res[$i]['home']  = $detail->home;
            $res[$i]['away']  = $detail->away;
            $res[$i]['score'] = $detail->score;
            $res[$i]['handi'] = $detail->handi;
            $res[$i]['league'] = $detail->league;
            $res[$i]['type']  = $type;

            $i++;
        }
        return $res;
    }

    public static function getTotal($rank, $type = "fuck")
    {
        $res['lostrate'] = ArrayHelper::getValue($rank, $type.'_datatotal.lostrate');
        $res['drawrate'] = ArrayHelper::getValue($rank, $type.'_datatotal.drawrate');
        $res['winrate']  = ArrayHelper::getValue($rank, $type.'_datatotal.winrate');
        $res['win']      = ArrayHelper::getValue($rank, $type.'_datatotal.win');
        $res['draw']     = ArrayHelper::getValue($rank, $type.'_datatotal.draw');
        $res['lost']     = ArrayHelper::getValue($rank, $type.'_datatotal.lost');
        $res['lostnum']  = ArrayHelper::getValue($rank, $type.'_datatotal.lostnum');
        $res['innum']    = ArrayHelper::getValue($rank, $type.'_datatotal.innum');
        $res['type']     = $type;

        return $res;
    }

    public static function getPower($rank, $type = "h")
    {
        $res['worth_score']   = ArrayHelper::getValue($rank, $type.'power.worth_score');
        $res['attack_score']  = ArrayHelper::getValue($rank, $type.'power.attack_score');
        $res['defend_score']  = ArrayHelper::getValue($rank, $type.'power.defend_score');
        $res['tech_score']    = ArrayHelper::getValue($rank, $type.'power.tech_score');
        $res['state_score']   = ArrayHelper::getValue($rank, $type.'power.state_score');
        $res['grade']         = ArrayHelper::getValue($rank, $type.'power.grade');
        $res['total_score']   = ArrayHelper::getValue($rank, $type.'power.total_score');
        $res['type']          = $type;

        return $res;
    }

    public static function getTmpOdd($odds, $tag)
    {
        $count = count($odds);
        $i = 0;
        $res = array();

        for ($key = 0; $key < $count; $key++)
        {
            if(isset($odds[$key]->time) && isset($odds[$key-1]->time) && (strtotime($odds[$key-1]->time) - strtotime($odds[$key]->time) >= 3600) && $key != $count-1){
                $res[$i]['tag'] = $tag;
                $res[$i]['time'] = $odds[$key]->time;
                $res[$i]['home'] = $odds[$key]->home;
                $res[$i]['away'] = $odds[$key]->away;
                $res[$i]['odd']  = $odds[$key]->handi;
                $res[$i]['home_text'] = $odds[$key]->s1 == 1 ? 'text-danger' : 'text-info';
                $res[$i]['away_text'] = $odds[$key]->s2 == 1 ? 'text-danger' : 'text-info';

                $i++;
            }
        }

        return $res;
    }

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
                        $res[$i][$lji_odd[$j]['tag']] = $lji_odd[$j];
                    }
                    elseif (isset($lji_odd[$j]) && strtotime($value['time']) == strtotime($lji_odd[$j]['time']))
                    {
                        $res[$i][$value['tag']] = $value;
                        $res[$i][$lji_odd[$j]['tag']] = $lji_odd[$j];
                        $j++;
                        break;
                    }
                    else
                    {
                        $res[$i][$value['tag']] = $value;
                        break;
                    }

                    $i++;
                }
            }
            else
            {
                $res[$i][$value['tag']] = $value;
            }
            $i++;
        }

        if($j < $count)
        {
            for ($key = $j; $key < $count; $key++)
            {
                $res[$i][$lji_odd[$key]['tag']] = $lji_odd[$key];
                $i++;
            }
        }

        return $res;
    }

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

    public static function matchOdd($fid, $vsdate)
    {
        $post_data = [
            "vsdate" => $vsdate,
            "fid"    => $fid,
            "t"      => 2,
            "c_key"  => "efff0a84f860ff38fe8f5abfa0a68496",
            "cid"    => 9,
            //9 易胜博
            //16  10BET
            //651 利己
            "c_id"   => 40020,
            "c_type" => 2,
            "c_cpid" => 2,
            "suid"	 => "51135b8f9224fabfe13e8ff68d18729c",
        ];

        try{
            $curl = new Curl("i.qqshidao.com", "/api/index.php", "POST", 80, true);
            $curl->setData($post_data);
            self::$LIJI_ODD = $curl->execute()->getResponseText();
            $post_data['cid'] = 16;
            $curl->setData($post_data);
            self::$BET_ODD = $curl->execute()->getResponseText();
            $curl->close();
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public static function matchRank($fid)
    {
        $post_data = [
            "fid"    => $fid,
            "c_key"  => "541ba457d67da316c6acbbd1e57004f5",
            "quid"   => 238952,
            //9 易胜博
            //651 利己
            //16  10BET
            "c_id"   => 41101,
            "c_type" => 2,
            "c_cpid" => 2,
            "suid"	 => "51135b8f9224fabfe13e8ff68d18729c",
        ];

        try{
            $curl = new Curl("106.75.147.59", "/api/index.php", "POST", 80, true);
            $curl->setData($post_data);
            self::$MATCH_RANK = $curl->execute()->getResponseText();
            $curl->close();
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    private static function isJson($string)
    {
        if(!is_string($string))
        {
            return false;
        }
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

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

    public static function calDiff($odds)
    {
        $count = count($odds);

        for ($key = 0; $key < $count-1; $key++)
        {
            $key_nxt = array_keys($odds[$key]);
            $key_pre = array_keys($odds[$key+1]);

            foreach($key_nxt as $nxt)
            {
                foreach($key_pre as $pre)
                {
                    if($pre !== $nxt && $odds[$key][$nxt]['odd'] == $odds[$key+1][$pre]['odd'])
                    {
                        $odds[$key][$nxt][$pre."_home"] = number_format($odds[$key][$nxt]['home'] - $odds[$key+1][$pre]['home'], 2);
                        $odds[$key][$nxt][$pre."_away"] = number_format($odds[$key][$nxt]['away'] - $odds[$key+1][$pre]['away'], 2);
                    }
                }
            }
        }
        return $odds;
    }

    public static function addDiffs($odds, $odd_key, $diff_key)
    {
        $count = count($odds);

        for ($key = $count-1; $key > 0; $key--)
        {
            if(isset($odds[$key][$odd_key][$diff_key."_home"]) && isset($odds[$key-1][$odd_key]) && !isset($odds[$key-1][$odd_key][$diff_key."_home"]))
            {
                $odds[$key-1][$odd_key][$diff_key."_home"] = number_format($odds[$key][$odd_key][$diff_key."_home"] + ($odds[$key-1][$odd_key]["home"] - $odds[$key][$odd_key]["home"]), 2);
                $odds[$key-1][$odd_key][$diff_key."_away"] = number_format($odds[$key][$odd_key][$diff_key."_away"] + ($odds[$key-1][$odd_key]["away"] - $odds[$key][$odd_key]["away"]), 2);
            }
        }

        return $odds;
    }

    public static function sameBet($target, $sames)
    {
        $home   = $target->b_home;
        $away   = $target->b_away;
        $handi  = $target->b_handi;

        foreach($sames as $key => $same)
        {
            if ($same->b_home == '0' || $same->b_away == '0' || $away == '0')
            {
                unset($sames[$key]);
                continue;
            }
            if(abs(number_format(floatval($same->b_home) / floatval($same->b_away), 3) - number_format(floatval($home) / floatval($away), 3)) <= self::getByte($home, $away) && $same->b_handi == $handi)
            {
                continue;
            }
            elseif (abs(number_format(floatval($same->b_away) / floatval($same->b_home), 3) - number_format(floatval($home) / floatval($away), 3)) <= self::getByte($home, $away) && str_replace('-', '', $same->b_handi) == str_replace('-', '', $handi) && (self::compare($home, $away) === self::compare($same->b_away, $same->b_home)) && $same->b_handi !== $handi)
            {
                continue;
            }else{
                unset($sames[$key]);
            }
        }

        return $sames;
    }

    public static function sameLji($target, $sames)
    {
        $home   = $target->l_home;
        $away   = $target->l_away;
        $handi  = $target->l_handi;

        foreach($sames as $key => $same)
        {
            if ($same->l_home == '0' || $same->l_away == '0' || $away == '0')
            {
                unset($sames[$key]);
                continue;
            }
            if(abs(number_format(floatval($same->l_home) / floatval($same->l_away), 3) - number_format(floatval($home) / floatval($away), 3)) <= self::getByte($home, $away) && $same->l_handi == $handi)
            {
                continue;
            }
            elseif (abs(number_format(floatval($same->l_away) / floatval($same->l_home), 3) - number_format(floatval($home) / floatval($away), 3)) <= self::getByte($home, $away) && str_replace('-', '', $same->l_handi) == str_replace('-', '', $handi) && (self::compare($home, $away) === self::compare($same->l_away, $same->l_home)) && $same->l_handi !== $handi)
            {
                continue;
            }else{
                unset($sames[$key]);
            }
        }

        return $sames;
    }

    public static function sameSim($target, $sames)
    {
        $home   = $target->s_home;
        $away   = $target->s_away;
        $handi  = $target->s_handi;

        foreach($sames as $key => $same)
        {
            if ($same->s_home == '0' || $same->s_away == '0' || $away == '0')
            {
                unset($sames[$key]);
                continue;
            }
            if(abs(number_format(floatval($same->s_home) / floatval($same->s_away), 3) - number_format(floatval($home) / floatval($away), 3)) <= self::getByte($home, $away) && $same->s_handi == $handi)
            {
                continue;
            }
            elseif (abs(number_format(floatval($same->s_away) / floatval($same->s_home), 3) - number_format(floatval($home) / floatval($away), 3)) <= self::getByte($home, $away) && str_replace('-', '', $same->s_handi) == str_replace('-', '', $handi) && (self::compare($home, $away) === self::compare($same->s_away, $same->s_home)) && $same->s_handi !== $handi)
            {
                continue;
            }else{
                unset($sames[$key]);
            }
        }

        return $sames;
    }


    private static function getByte($home, $away)
    {
        return floatval($home) > floatval($away) ? 0.085 : 0.060;
    }

    private static function compare($home, $away)
    {
        return floatval($home) > floatval($away) ? true : false;
    }

    public static function getMatch($data)
    {
        $res = array();

        if(isset($data['fid']))
        {
            $res['fid']   = $data['fid'];
            $res['mdate'] = $data['vsdate'];
            $res['hname'] = $data['hname'];
            $res['aname'] = $data['aname'];
            $res['score'] = $data['hscore']. ":" .$data['ascore'];
        }

        return $res;
    }

    public static function getMatchObj($data)
    {
        $res = array();

        if(isset($data->fid))
        {
            $res['fid']   = $data->fid;
            $res['mdate'] = $data->vsdate;
            $res['hname'] = $data->hname;
            $res['aname'] = $data->aname;
            $res['score'] = $data->hscore. ":" .$data->ascore;
        }

        return $res;
    }

    public static function store($match)
    {
        ini_set ('memory_limit', '512M');
        try
        {
            $transaction = Yii::$app->getDb()->beginTransaction();

            $exist = Match::findOne(['fid' => $match['fid']]);

            if(!is_null($exist))
            {
                if(!$exist->delete())
                {
                    throw new \Exception('match fail delete');
                }
            }

            $model = new Match();
            $model->isNewRecord == true;

            $model->setAttributes($match);

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
                $same->b_home = isset($model->getBetFirstOdd()['home']) ? $model->getBetFirstOdd()['home'] : '0';
                $same->b_away = isset($model->getBetFirstOdd()['away']) ? $model->getBetFirstOdd()['away'] : '0';
                $same->b_handi = isset($model->getBetFirstOdd()['odd']) ? $model->getBetFirstOdd()['odd'] : '0';
                $same->l_away = isset($model->getLjiFirstOdd()['away']) ? $model->getLjiFirstOdd()['away'] : '0';
                $same->l_home = isset($model->getLjiFirstOdd()['home']) ? $model->getLjiFirstOdd()['home'] : '0';
                $same->l_handi = isset($model->getLjiFirstOdd()['odd']) ? $model->getLjiFirstOdd()['odd'] : '0';
                $same->s_away = isset($model->getBetSameLjiOdd()['away']) ? $model->getBetSameLjiOdd()['away'] : '0';
                $same->s_handi = isset($model->getBetSameLjiOdd()['odd']) ? $model->getBetSameLjiOdd()['odd'] : '0';
                $same->s_home = isset($model->getBetSameLjiOdd()['home']) ? $model->getBetSameLjiOdd()['home'] : '0';
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
    }
}

