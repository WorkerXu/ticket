<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rank".
 *
 * @property integer $id
 * @property string $name
 * @property string $win
 * @property string $draw
 * @property string $lost
 * @property string $score
 * @property string $innum
 * @property string $lostnum
 * @property string $cwin
 * @property string $cdraw
 * @property string $clost
 * @property string $cscore
 * @property string $cinnum
 * @property string $clostnum
 * @property string $type
 * @property integer $match_id
 * @property string $standing
 * @property string $cstanding
 *
 * @property Match $match
 */
class Rank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'win', 'draw', 'lost', 'score', 'innum', 'lostnum', 'cwin', 'cdraw', 'clost', 'cscore', 'cinnum', 'clostnum', 'match_id', 'standing', 'cstanding'], 'required'],
            [['type'], 'string'],
            [['match_id'], 'integer'],
            [['name', 'win', 'draw', 'lost', 'score', 'innum', 'lostnum', 'cwin', 'cdraw', 'clost', 'cscore', 'cinnum', 'clostnum', 'standing', 'cstanding'], 'string', 'max' => 15],
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
            'name' => 'Name',
            'win' => 'Win',
            'draw' => 'Draw',
            'lost' => 'Lost',
            'score' => 'Score',
            'innum' => 'Innum',
            'lostnum' => 'Lostnum',
            'cwin' => 'Cwin',
            'cdraw' => 'Cdraw',
            'clost' => 'Clost',
            'cscore' => 'Cscore',
            'cinnum' => 'Cinnum',
            'clostnum' => 'Clostnum',
            'type' => 'Type',
            'match_id' => 'Match ID',
            'standing' => 'Standing',
            'cstanding' => 'Cstanding',
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