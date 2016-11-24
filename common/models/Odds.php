<?php
namespace common\models;

use yii\base\Model;
use common\helpers\Curl;

/**
 * Odds model
 *
 */

class Odds extends Model
{

    private static $BET_ODD;
    private static $LIJI_ODD;

    public static function getBetOdd()
    {
        return self::isJson(self::$BET_ODD) ? self::$BET_ODD : '{}';
    }

    public static function getLijiOdd()
    {
        return self::isJson(self::$LIJI_ODD) ? self::$LIJI_ODD : '{}';
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
            $curl = new Curl("123.59.67.6", "/api/index.php", "POST", 80, true);
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
            "cid"    => 651,
            "c_id"   => 40020,
            "c_type" => 2,
            "c_cpid" => 2,
            "suid"	 => "51135b8f9224fabfe13e8ff68d18729c",
            "quid"   => 238952
        ];

        try{
            $curl = new Curl("123.59.67.6", "/api/index.php", "POST", 80, true);
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
                    elseif ($match->isjczq !== $params['type'])
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
}

