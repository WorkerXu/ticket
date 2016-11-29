<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "detail".
 *
 * @property integer $id
 * @property string $fid
 * @property string $date
 * @property string $home
 * @property string $away
 * @property string $score
 * @property string $handi
 * @property string $type
 * @property integer $match_id
 * @property string $league
 *
 * @property Match $match
 */
class Detail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid', 'date', 'home', 'away', 'score', 'handi', 'match_id', 'league'], 'required'],
            [['date'], 'safe'],
            [['type'], 'string'],
            [['match_id'], 'integer'],
            [['fid', 'home', 'away', 'score', 'handi', 'league'], 'string', 'max' => 15],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => Match::className(), 'targetAttribute' => ['match_id' => 'id']],
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
            'date' => 'Date',
            'home' => 'Home',
            'away' => 'Away',
            'score' => 'Score',
            'handi' => 'Handi',
            'type' => 'Type',
            'match_id' => 'Match ID',
            'league' => 'League',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch()
    {
        return $this->hasOne(Match::className(), ['id' => 'match_id']);
    }
}