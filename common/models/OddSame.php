<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "odd_same".
 *
 * @property integer $id
 * @property string $l_home
 * @property string $l_away
 * @property string $b_home
 * @property string $b_away
 * @property string $l_handi
 * @property string $b_handi
 * @property string $s_home
 * @property string $s_away
 * @property string $s_handi
 * @property integer $match_id
 *
 * @property Match $match
 */
class OddSame extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'odd_same';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_home', 'l_away', 'b_home', 'b_away', 'l_handi', 'b_handi', 's_home', 's_away', 's_handi', 'match_id'], 'required'],
            [['match_id'], 'integer'],
            [['l_home', 'l_away', 'b_home', 'b_away', 'l_handi', 'b_handi', 's_home', 's_away', 's_handi'], 'string', 'max' => 15],
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
            'l_home' => 'L Home',
            'l_away' => 'L Away',
            'b_home' => 'B Home',
            'b_away' => 'B Away',
            'l_handi' => 'L Handi',
            'b_handi' => 'B Handi',
            's_home' => 'S Home',
            's_away' => 'S Away',
            's_handi' => 'S Handi',
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
