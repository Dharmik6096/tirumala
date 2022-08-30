<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_device_master_mapping_history".
 *
 * @property integer $id
 * @property string $device_master_code
 * @property string $applicability_code
 * @property string $applicability_type
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblDeviceMasterMappingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_master_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['device_master_code'], 'required'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['originating_type'], 'safe'],
            [['device_master_code', 'applicability_code', 'originating_org_type', 'originating_org_code'], 'safe'],
            [['applicability_type', 'created_by', 'updated_by'], 'safe'],
            [['operation_type'], 'safe'],
            [['history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'device_master_code' => Yii::t('app', 'Device Master Code'),
            'applicability_code' => Yii::t('app', 'Applicability Code'),
            'applicability_type' => Yii::t('app', 'Applicability Type'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
