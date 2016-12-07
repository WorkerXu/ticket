<?php

namespace common\models;

/**
 * This is the model class for table "match".
 *
 * @property integer $id
 * @property integer $fid
 * @property string $hname
 * @property string $aname
 * @property string $score
 * @property string $mdate
 *
 * @property Odd $id0
 * @property Odd[] $odds
 */
class Match extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'match';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid', 'hname', 'aname', 'score', 'mdate'], 'required'],
            [['fid'], 'integer'],
            [['mdate'], 'safe'],
            [['hname', 'aname', 'score'], 'string', 'max' => 15],
            [['fid'], 'unique'],
            [['text'], 'string', 'max' => 255],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Odd::className(), 'targetAttribute' => ['id' => 'match_id']],
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
            'hname' => 'Hname',
            'aname' => 'Aname',
            'score' => 'Score',
            'mdate' => 'Mdate',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOdds()
    {
        return $this->hasMany(Odd::className(), ['match_id' => 'id']);
    }

    public function getBetOdds()
    {
        return Odd::find()->where(['match_id' => $this->id, 'tag' => 'bet'])->orderBy(['time' => SORT_DESC])->asArray()->all();
    }

    public function getLjiOdds()
    {
        return Odd::find()->where(['match_id' => $this->id, 'tag' => 'lji'])->orderBy(['time' => SORT_DESC])->asArray()->all();
    }

    public function getBetFirstOdd()
    {
        return Odd::find()->where(['match_id' => $this->id, 'tag' => 'bet'])->orderBy(['time' => SORT_ASC])->limit(1)->asArray()->one();
    }

    public function getLjiFirstOdd()
    {
        return Odd::find()->where(['match_id' => $this->id, 'tag' => 'lji'])->orderBy(['time' => SORT_ASC])->limit(1)->asArray()->one();
    }

    public function getBetSameLjiOdd()
    {
        $lji = $this->getLjiFirstOdd();

        if(isset($lji['time']))
        {
            return Odd::find()->where(['match_id' => $this->id, 'tag' => 'bet'])->andWhere(['<=', 'time', $lji['time']])->orderBy(['time' => SORT_DESC])->limit(1)->asArray()->one();
        }
        return array();
    }

    public function getOddSame()
    {
        return $this->hasOne(OddSame::className(), ['match_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRanks()
    {
        return $this->hasMany(Rank::className(), ['match_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFuckDetails()
    {
        return Detail::find()->where(['match_id' => $this->id, 'type' => 'fuck'])->all();
    }

    public function getHomeDetails()
    {
        return Detail::find()->where(['match_id' => $this->id, 'type' => 'home'])->all();
    }

    public function getAwayDetails()
    {
        return Detail::find()->where(['match_id' => $this->id, 'type' => 'away'])->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFuckTotal()
    {
        return Total::find()->where(['match_id' => $this->id, 'type' => 'fuck'])->all();
    }

    public function getHomeTotal()
    {
        return Total::find()->where(['match_id' => $this->id, 'type' => 'home'])->all();
    }

    public function getAwayTotal()
    {
        return Total::find()->where(['match_id' => $this->id, 'type' => 'away'])->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPowers()
    {
        return $this->hasMany(Power::className(), ['match_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocre()
    {
        return $this->hasOne(Socre::className(), ['match_id' => 'id']);
    }
}
