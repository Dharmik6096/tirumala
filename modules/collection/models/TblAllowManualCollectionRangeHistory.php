<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_allow_manual_collection_range_history".
 *
 * @property integer $id
 * @property integer $allow_manual_collection_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $from_shift
 * @property string $to_date
 * @property string $to_shift
 * @property integer $is_weight_manual
 * @property integer $is_quality_manual
 * @property string $remark
 * @property integer $is_approved
 * @property string $entry_type
 * @property string $table_name
 * @property string $application_type
 * @property string $approved_at
 * @property string $approved_by
 * @property string $approval_status
 * @property string $complain_type
 * @property integer $complain_status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblAllowManualCollectionRangeHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_allow_manual_collection_range_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'entry_type', 'approval_status', 'from_date', 'from_shift', 'to_shift', 'table_name', 'application_type', 'complain_type', 'to_date', 'approved_at', 'created_at', 'updated_at', 'history_created_at', 'allow_manual_collection_code', 'is_weight_manual', 'is_quality_manual', 'is_approved', 'complain_status', 'originating_type', 'remark', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'approved_by', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'action_perform', 'complain_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'allow_manual_collection_code' => Yii::t('app', 'Allow Manual Collection Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'is_weight_manual' => Yii::t('app', 'Is Weight Manual'),
            'is_quality_manual' => Yii::t('app', 'Is Quality Manual'),
            'remark' => Yii::t('app', 'Remark'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'table_name' => Yii::t('app', 'Table Name'),
            'application_type' => Yii::t('app', 'Application Type'),
            'approved_at' => Yii::t('app', 'Approved At'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'approval_status' => Yii::t('app', 'Approval Status'),
            'complain_type' => Yii::t('app', 'Complain Type'),
            'complain_status' => Yii::t('app', 'Complain Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
