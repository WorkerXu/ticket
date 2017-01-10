<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "matchs".
 *
 * @property integer $id
 * @property integer $fid
 * @property string $mdate
 * @property string $league
 * @property string $hname
 * @property string $aname
 * @property string $score
 *
 * @property Odds[] $odds
 * @property Sames[] $sames
 * @property Scores[] $scores
 */
class Matchs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matchs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid'], 'required'],
            [['fid'], 'integer'],
            [['mdate'], 'safe'],
            [['league', 'hname', 'aname', 'score'], 'string', 'max' => 15],
            [['fid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fid' => 'Fid',
            'mdate' => 'Mdate',
            'league' => 'League',
            'hname' => 'Hname',
            'aname' => 'Aname',
            'score' => 'Score',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOdds()
    {
        return $this->hasMany(Odds::className(), ['match_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSames()
    {
        return $this->hasMany(Sames::className(), ['match_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScores()
    {
        return $this->hasMany(Scores::className(), ['match_id' => 'id']);
    }

    //获取sim赔率
    public function bet()
    {
        return Odds::find()->where(['match_id' => $this->id, 'tag' => 'bet'])->orderBy(['time' => SORT_ASC])->limit(1)->asArray()->one();
    }

    public function ysb()
    {
        return Odds::find()->where(['match_id' => $this->id, 'tag' => 'ysb'])->orderBy(['time' => SORT_ASC])->limit(1)->asArray()->one();
    }

    public function aom()
    {
        return Odds::find()->where(['match_id' => $this->id, 'tag' => 'aom'])->orderBy(['time' => SORT_ASC])->limit(1)->asArray()->one();
    }

    public function ays()
    {
        $aom = $this->aom();

        if (isset($aom['time'])) {
            return Odds::find()->where(['match_id' => $this->id, 'tag' => 'ysb'])->andWhere(['<=', 'time', $aom['time']])->orderBy(['time' => SORT_DESC])->limit(1)->asArray()->one();
        }
        return array();
    }

    public function ybs()
    {
        $ysb = $this->ysb();

        if (isset($ysb['time'])) {
            return Odds::find()->where(['match_id' => $this->id, 'tag' => 'bet'])->andWhere(['<=', 'time', $ysb['time']])->orderBy(['time' => SORT_DESC])->limit(1)->asArray()->one();
        }
        return array();
    }
    //end
}
