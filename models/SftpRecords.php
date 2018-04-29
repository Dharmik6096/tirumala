<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sftp_records".
 *
 * @property integer $id
 * @property string $status
 * @property string $updated_at
 */
class SftpRecords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sftp_records';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'string'],
            [['updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return SftpRecordsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SftpRecordsQuery(get_called_class());
    }
}
