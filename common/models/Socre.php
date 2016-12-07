<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "socre".
 *
 * @property integer $id
 * @property integer $socre
 * @property integer $hmatch
 * @property integer $amatch
 * @property integer $fmatch
 * @property integer $famcth
 * @property integer $shmatch
 * @property integer $thmatch
 * @property integer $samatch
 * @property integer $tamatch
 * @property integer $match_id
 * @property string $text
 *
 * @property Match $match
 */
class Socre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['socre', 'hmatch', 'amatch', 'fmatch', 'famcth', 'shmatch', 'thmatch', 'samatch', 'tamatch', 'match_id'], 'integer'],
            [['socre', 'hmatch', 'amatch', 'fmatch', 'famcth', 'shmatch', 'thmatch', 'samatch', 'tamatch'], 'default', 'value' => 0],
            [['match_id'], 'required'],
            [['text'], 'string', 'max' => 255],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => Match::className(), 'targetAttribute' => ['match_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'socre' => '积分',
            'hmatch' => '总战绩',
            'amatch' => '主客战绩',
            'fmatch' => '对阵战绩',
            'famcth' => '对阵主客战绩 ',
            'shmatch' => '近十场战绩',
            'thmatch' => '近三场走势',
            'samatch' => '近十场主客战绩',
            'tamatch' => '近三场主客走势',
            'match_id' => 'Match ID',
            'text' => '描述',
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
