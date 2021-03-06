<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "odds".
 *
 * @property integer $id
 * @property string $tag
 * @property string $time
 * @property string $home
 * @property string $away
 * @property string $odd
 * @property string $home_text
 * @property string $away_text
 * @property integer $match_id
 *
 * @property Matchs $match
 */
class Odds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'odds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time'], 'safe'],
            [['match_id'], 'required'],
            [['match_id'], 'integer'],
            [['tag'], 'string', 'max' => 50],
            [['home', 'away', 'odd', 'home_text', 'away_text'], 'string', 'max' => 15],
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
            'time' => 'Time',
            'home' => 'Home',
            'away' => 'Away',
            'odd' => 'Odd',
            'home_text' => 'Home Text',
            'away_text' => 'Away Text',
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
