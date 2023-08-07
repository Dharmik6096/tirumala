<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_attachment_history".
 *
 * @property integer $id
 * @property integer $attachment_code
 * @property string $module_name
 * @property string $module_code
 * @property string $attachment_type
 * @property string $remarks
 * @property string $attachment
 * @property string $file_name
 * @property string $lat_long
 * @property string $parent_code
 * @property string $device_id
 * @property string $thumbnail
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblAttachmentHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_attachment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['attachment_code', 'originating_type', 'remarks', 'parent_code', 'device_id', 'file_name', 'attachment', 'thumbnail', 'created_at', 'updated_at', 'history_created_at', 'module_name', 'lat_long', 'module_code', 'attachment_type', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'attachment_code' => Yii::t('app', 'Attachment Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'attachment_type' => Yii::t('app', 'Attachment Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'attachment' => Yii::t('app', 'Attachment'),
            'file_name' => Yii::t('app', 'File Name'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'parent_code' => Yii::t('app', 'Parent Code'),
            'device_id' => Yii::t('app', 'Device ID'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
