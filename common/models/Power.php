<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "power".
 *
 * @property integer $id
 * @property string $worth_score
 * @property string $attack_score
 * @property string $defend_score
 * @property string $tech_score
 * @property string $state_score
 * @property string $grade
 * @property string $total_score
 * @property string $type
 * @property integer $match_id
 *
 * @property Match $match
 */
class Power extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'power';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worth_score', 'attack_score', 'defend_score', 'tech_score', 'state_score', 'grade', 'total_score', 'type', 'match_id'], 'required'],
            [['type'], 'string'],
            [['match_id'], 'integer'],
            [['worth_score', 'attack_score', 'defend_score', 'tech_score', 'state_score', 'grade', 'total_score'], 'string', 'max' => 15],
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
            'worth_score' => 'Worth Score',
            'attack_score' => 'Attack Score',
            'defend_score' => 'Defend Score',
            'tech_score' => 'Tech Score',
            'state_score' => 'State Score',
            'grade' => 'Grade',
            'total_score' => 'Total Score',
            'type' => 'Type',
            'match_id' => 'Match ID',
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
