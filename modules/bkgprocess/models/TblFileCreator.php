<?php

namespace app\modules\bkgprocess\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_file_creator".
 *
 * @property integer $file_creator_id
 * @property string $module_name
 * @property string $module_code
 * @property string $mcc_plant_code
 * @property string $union_code
 * @property string $applicable_date
 * @property string $shift_code
 * @property string $process_type
 * @property string $file_type
 * @property string $vendor_code
 * @property integer $file_status
 * @property integer $status
 * @property string $activity_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblFileCreator extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_file_creator';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['file_status', 'status'], 'default', 'value' => 0],
            [['activity_type'], 'default', 'value' => 'AUTO'],
            [['vendor_code'], 'default', 'value' => 'EIPL'],
            [['process_type'], 'default', 'value' => 'TRANSACTION'],
            [['file_type'], 'default', 'value' => 'COLLECTION'],
            [['module_name', 'module_code', 'mcc_plant_code', 'union_code', 'process_type', 'file_type', 'vendor_code', 'activity_type', 'created_by', 'updated_by'], 'string'],
            [['applicable_date', 'shift_code', 'created_at', 'updated_at'], 'safe'],
            [['file_status', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'file_creator_id' => Yii::t('app', 'File Creator ID'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'applicable_date' => Yii::t('app', 'Applicable Date'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'process_type' => Yii::t('app', 'Process Type'),
            'file_type' => Yii::t('app', 'File Type'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'file_status' => Yii::t('app', 'File Status'),
            'status' => Yii::t('app', 'Status'),
            'activity_type' => Yii::t('app', 'Activity Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblFileCreatorQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblFileCreatorQuery(get_called_class());
    }

    public function getPendingData() {
        return $this->find()
                        ->where(['file_status' => $this->file_status, 'status' => $this->status])
                        ->limit(25)
                        ->all();
    }

    public function updateFileStatus($ids) {
        return $this->updateAll(['status' => 1, 'updated_at' => date('Y-m-d H:i:s')], ['file_creator_id' => $ids]);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

}
