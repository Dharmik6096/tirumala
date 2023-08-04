<?php

namespace app\modules\document\models;

use Yii;

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
                [['doc_id', 'originating_type'], 'integer'],
                [['attachment', 'thumbnail'], 'string'],
                [['created_at'], 'safe'],
                [['module_code', 'attachment_type', 'created_by'], 'string', 'max' => 20],
                [['module_name', 'lat_long', 'updated_at', 'updated_by'], 'string', 'max' => 255],
                [['remarks', 'file_name'], 'string', 'max' => 1000],
                [['parent_code'], 'string', 'max' => 50],
                [['device_id'], 'string', 'max' => 500],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'attachment_code' => Yii::t('app', 'Attachment Code'),
            'doc_id' => Yii::t('app', 'Doc ID'),
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

}
