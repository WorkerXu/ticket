<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "total".
 *
 * @property integer $id
 * @property string $lostrate
 * @property string $drawrate
 * @property string $winrate
 * @property string $lost
 * @property string $win
 * @property string $draw
 * @property string $lostnum
 * @property string $innum
 * @property string $type
 * @property integer $match_id
 *
 * @property Match $match
 */
class Total extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'total';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lostrate', 'drawrate', 'winrate', 'lost', 'win', 'draw', 'lostnum', 'innum', 'match_id'], 'required'],
            [['type'], 'string'],
            [['match_id'], 'integer'],
            [['lostrate', 'drawrate', 'winrate', 'lost', 'win', 'draw', 'lostnum', 'innum'], 'string', 'max' => 15],
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
            'lostrate' => 'Lostrate',
            'drawrate' => 'Drawrate',
            'winrate' => 'Winrate',
            'lost' => 'Lost',
            'win' => 'Win',
            'draw' => 'Draw',
            'lostnum' => 'Lostnum',
            'innum' => 'Innum',
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
