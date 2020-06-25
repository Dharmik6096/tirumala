<?php

namespace app\modules\bkgprocess\models;

use Yii;

/**
 * This is the model class for table "tbl_org_file_creator".
 *
 * @property integer $org_file_creator_id
 * @property string $module_name
 * @property string $module_code
 * @property string $file_type
 * @property string $vendor_code
 * @property integer $file_status
 * @property integer $status
 * @property string $pick_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $value1
 */
class TblOrgFileCreator extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_org_file_creator';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['module_name', 'module_code', 'file_type', 'vendor_code', 'created_by', 'updated_by', 'value1'], 'string'],
            [['file_status', 'status'], 'integer'],
            [['pick_datetime', 'created_at', 'updated_at', 'ref_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'org_file_creator_id' => Yii::t('app', 'Org File Creator ID'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'file_type' => Yii::t('app', 'File Type'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'file_status' => Yii::t('app', 'File Status'),
            'status' => Yii::t('app', 'Status'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'value1' => Yii::t('app', 'Value1'),
        ];
    }

    public function getPendingData() {
        return $this->find()
                        ->where(['file_status' => $this->file_status, 'status' => $this->status])
                        ->limit(25)
                        ->all();
    }

    public function updateFileStatus($value) {
        return $this->updateAll(['status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['org_file_creator_id' => $value]);
    }

}
