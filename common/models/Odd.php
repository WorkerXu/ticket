<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "odd".
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
 * @property Match $match
 * @property Match $match0
 */
class Odd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'odd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag', 'time', 'home', 'away', 'odd', 'home_text', 'away_text', 'match_id'], 'required'],
            [['time'], 'safe'],
            [['match_id'], 'integer'],
            [['tag'], 'string', 'max' => 8],
            [['home', 'away', 'odd', 'home_text', 'away_text'], 'string', 'max' => 15],
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
        return $this->hasOne(Match::className(), ['id' => 'match_id']);
    }


}
