<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_channel_master".
 *
 * @property string $channel_master_code
 * @property string $channel_desc
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 */
class TblChannelMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_channel_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channel_master_code'], 'required'],
            [['is_active'], 'integer'],
            [['created_at'], 'safe'],
            [['channel_master_code'], 'string', 'max' => 25],
            [['channel_desc'], 'string', 'max' => 50],
            [['created_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'channel_master_code' => Yii::t('app', 'Channel Master Code'),
            'channel_desc' => Yii::t('app', 'Channel Desc'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }
}
