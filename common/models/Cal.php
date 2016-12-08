<?php
/**
 * Created by PhpStorm.
 * User: xutao
 * Date: 16/12/8
 * Time: 11:21
 */

namespace common\models;

use yii\base\Model;

class Cal extends Model
{

    public $hsum;
    public $hscore;
    public $hasocre;
    public $asum;
    public $ascore;
    public $aasocre;
    public $hmatchw;
    public $hmatchd;
    public $hmatchl;
    public $amatchw;
    public $amatchd;
    public $amatchl;
    public $hamatchw;
    public $hamatchd;
    public $hamatchl;
    public $aamatchw;
    public $aamatchd;
    public $aamatchl;
    public $fw;
    public $fd;
    public $fl;
    public $fhw;
    public $fhd;
    public $fhl;
    public $fsw;
    public $fsd;
    public $fsl;
    public $hjw;
    public $hjd;
    public $hjl;
    public $ajw;
    public $ajd;
    public $ajl;
    public $hjsw;
    public $hjsd;
    public $hjsl;
    public $ajsw;
    public $ajsd;
    public $ajsl;
    public $hajw;
    public $hajd;
    public $hajl;
    public $aajw;
    public $aajd;
    public $aajl;
    public $hajsw;
    public $hajsd;
    public $hajsl;
    public $aajsw;
    public $aajsd;
    public $aajsl;

    public function rules()
    {
        return [
            [$this->getAllattr(), 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            "hscore" => "主队总积分",
            "ascore" => "客队总积分",
            "hsum" => "总场数",
            "hasocre" => "主队主积分",
            "aasocre" => "客队客积分",
            "asum" => "主客场数",
            "hmatchw" => "主队总战绩胜",
            "hmatchd" => "主队总战绩平",
            "hmatchl" => "主队总战绩负",
            "amatchw" => "客队总战绩胜",
            "amatchd" => "客队总战绩平",
            "amatchl" => "客队总战绩负",
            "hamatchw" => "主队主战绩胜",
            "hamatchd" => "主队主战绩平",
            "hamatchl" => "主队主战绩负",
            "aamatchw" => "客队客战绩胜",
            "aamatchd" => "客队客战绩平",
            "aamatchl" => "客队客战绩负",
            "fw" => "对阵胜",
            "fd" => "对阵平",
            "fl" => "对阵负",
            "fhw" => "主对阵胜",
            "fhd" => "主对阵平",
            "fhl" => "主对阵负",
            "fsw" => "三场对阵胜",
            "fsd" => "三场对阵平",
            "fsl" => "三场对阵负",
            "hjw" => "主队十场胜",
            "hjd" => "主队十场平",
            "hjl" => "主队十场负",
            "ajw" => "客队十场胜",
            "ajd" => "客队十场平",
            "ajl" => "客队十场负",
            "hjsw" => "主队三场胜",
            "hjsd" => "主队三场平",
            "hjsl" => "主队三场负",
            "ajsw" => "客队三场胜",
            "ajsd" => "客队三场平",
            "ajsl" => "客队三场负",
            "hajw" => "主队十场主场胜",
            "hajd" => "主队十场主场平",
            "hajl" => "主队十场主场负",
            "aajw" => "客队十场客场胜",
            "aajd" => "客队十场客场平",
            "aajl" => "客队十场客场负",
            "hajsw" => "主队三场主场胜",
            "hajsd" => "主队三场主场平",
            "hajsl" => "主队三场主场负",
            "aajsw" => "客队三场客场胜",
            "aajsd" => "客队三场客场平",
            "aajsl" => "客队三场客场负",
        ];
    }

    private function getAllattr()
    {
        return[
            "hscore",
            "ascore",
            "hsum",
            "hasocre",
            "aasocre",
            "asum",
            "hmatchw",
            "hmatchd",
            "hmatchl",
            "amatchw",
            "amatchd",
            "amatchl",
            "hamatchw",
            "hamatchd",
            "hamatchl",
            "aamatchw",
            "aamatchd",
            "aamatchl",
            "fw",
            "fd",
            "fl",
            "fhw",
            "fhd",
            "fhl",
            "fsw",
            "fsd",
            "fsl",
            "hjw",
            "hjd",
            "hjl",
            "ajw",
            "ajd",
            "ajl",
            "hjsw",
            "hjsd",
            "hjsl",
            "ajsw",
            "ajsd",
            "ajsl",
            "hajw",
            "hajd",
            "hajl",
            "aajw",
            "aajd",
            "aajl",
            "hajsw",
            "hajsd",
            "hajsl",
            "aajsw",
            "aajsd",
            "aajsl",
        ];
    }
}
