<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_member_download_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $download_id
 * @property string $dcs_code
 * @property integer $is_download
 * @property string $download_datetime
 * @property string $upload_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMemberDownloadHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member_download_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'download_datetime', 'upload_datetime', 'created_at', 'updated_at'], 'safe'],
            [['operation_type', 'dcs_code', 'created_by', 'updated_by'], 'string'],
            [['download_id', 'is_download'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'download_id' => Yii::t('app', 'Download ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'is_download' => Yii::t('app', 'Is Download'),
            'download_datetime' => Yii::t('app', 'Download Datetime'),
            'upload_datetime' => Yii::t('app', 'Upload Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
