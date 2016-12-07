<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "socre".
 *
 * @property integer $id
 * @property integer $hsocre
 * @property integer $asocre
 * @property integer $ihsocre
 * @property integer $iasocre
 * @property integer $hmatch
 * @property integer $amatch
 * @property integer $fmatch
 * @property integer $famcth
 * @property integer $ftmacth
 * @property integer $shmatch
 * @property integer $ishmatch
 * @property integer $thmatch
 * @property integer $ithmatch
 * @property integer $samatch
 * @property integer $isamatch
 * @property integer $tamatch
 * @property integer $itamatch
 * @property integer $power
 * @property string $text
 * @property integer $match_id
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
            [['hsocre', 'asocre', 'ihsocre', 'iasocre', 'hmatch', 'amatch', 'fmatch', 'famcth', 'ftmacth', 'shmatch', 'ishmatch', 'thmatch', 'ithmatch', 'samatch', 'isamatch', 'tamatch', 'itamatch', 'power', 'match_id'], 'integer'],
            [['hsocre', 'asocre', 'ihsocre', 'iasocre', 'hmatch', 'amatch', 'fmatch', 'famcth', 'ftmacth', 'shmatch', 'ishmatch', 'thmatch', 'ithmatch', 'samatch', 'isamatch', 'tamatch', 'itamatch', 'power'], 'default', 'value' => 0],
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
            'hsocre' => '积分',
            'asocre' => '主客积分',
            'ihsocre' => '积分状态',
            'iasocre' => '主客积分状态',
            'hmatch' => '总战绩',
            'amatch' => '主客战绩',
            'fmatch' => '对阵战绩',
            'famcth' => '对阵主客战绩 ',
            'ftmacth' => '近三场对阵战绩',
            'shmatch' => '近十场战绩',
            'ishmatch' => '近十场战绩状态',
            'thmatch' => '近三场走势',
            'ithmatch' => '近三场战绩状态',
            'samatch' => '近十场主客战绩',
            'isamatch' => '近十场主客战绩状态',
            'tamatch' => '近三场主客走势',
            'itamatch' => '近三场主客战绩状态',
            'power' => '球球实力对比',
            'text' => '描述',
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
