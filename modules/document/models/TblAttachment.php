<?php

namespace app\modules\document\models;

use Yii;
use app\modules\welfarescheme\models\TblDocumentMasterInfo;
use app\modules\document\models\TblDocumentMapping;

/**
 * This is the model class for table "tbl_attachment".
 *
 * @property integer $attachment_code
 * @property integer $doc_id
 * @property string $module_code
 * @property string $module_name
 * @property string $attachment_type
 * @property string $remarks
 * @property string $file_name
 * @property string $attachment
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
 */
class TblAttachment extends \app\models\ChildModel {

    public $doc_name, $is_mandate;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_mandate', 'doc_name', 'doc_id', 'parent_code', 'device_id', 'originating_org_code', 'originating_org_type', 'originating_type', 'attachment', 'thumbnail', 'created_at', 'module_code', 'attachment_type', 'created_by', 'module_name', 'lat_long', 'updated_at', 'updated_by', 'remarks', 'file_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'attachment_code' => Yii::t('app', 'Attachment Code'),
            'doc_id' => Yii::t('app', 'Document Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'attachment_type' => Yii::t('app', 'Attachment Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'file_name' => Yii::t('app', 'File Name'),
            'attachment' => Yii::t('app', 'Attachment'),
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
        ];
    }

    public function getDocId() {
        return $this->hasOne(TblDocumentMasterInfo::className(), ['doc_id' => 'doc_id']);
    }

}
