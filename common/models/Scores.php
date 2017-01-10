<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "scores".
 *
 * @property integer $id
 * @property string $tag
 * @property string $win
 * @property string $draw
 * @property string $lost
 * @property integer $match_id
 *
 * @property Matchs $match
 */
class Scores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id'], 'required'],
            [['match_id'], 'integer'],
            [['tag'], 'string', 'max' => 50],
            [['win', 'draw', 'lost'], 'string', 'max' => 15],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => Matchs::className(), 'targetAttribute' => ['match_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag' => 'Tag',
            'win' => 'Win',
            'draw' => 'Draw',
            'lost' => 'Lost',
            'match_id' => 'Match ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch()
    {
        return $this->hasOne(Matchs::className(), ['id' => 'match_id']);
    }
}
