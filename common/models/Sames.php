<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sames".
 *
 * @property integer $id
 * @property string $tag
 * @property string $home
 * @property string $away
 * @property string $handi
 * @property integer $match_id
 *
 * @property Matchs $match
 */
class Sames extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sames';
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
            [['home', 'away', 'handi'], 'string', 'max' => 15],
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
            'home' => 'Home',
            'away' => 'Away',
            'handi' => 'Handi',
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
