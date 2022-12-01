<?php

namespace app\modules\welfarescheme\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_application".
 *
 * @property integer $application_id
 * @property integer $scheme_id
 * @property string $member_code
 * @property string $application_date
 * @property string $min_pouring_day
 * @property string $min_pouring_qty
 * @property string $actual_pouring_day
 * @property string $actual_pouring_qty
 * @property string $remarks
 * @property string $scheme_value
 * @property string $approved_value
 * @property string $application_status
 * @property string $status_date
 * @property string $status_by
 * @property string $status_remarks
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSchemeApplication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_scheme_application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scheme_id', 'originating_type'], 'integer'],
            [['application_date', 'status_date', 'created_at', 'updated_at'], 'safe'],
            [['min_pouring_day', 'min_pouring_qty', 'actual_pouring_day', 'actual_pouring_qty', 'scheme_value', 'approved_value'], 'number'],
            [['member_code', 'application_status', 'status_by'], 'string', 'max' => 20],
            [['remarks', 'status_remarks'], 'string', 'max' => 255],
            [['dcs_code', 'bmc_code'], 'string', 'max' => 12],
            [['mcc_plant_code', 'plant_code'], 'string', 'max' => 6],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'application_id' => 'Application ID',
            'scheme_id' => 'Scheme ID',
            'member_code' => 'Member Code',
            'application_date' => 'Application Date',
            'min_pouring_day' => 'Min Pouring Day',
            'min_pouring_qty' => 'Min Pouring Qty',
            'actual_pouring_day' => 'Actual Pouring Day',
            'actual_pouring_qty' => 'Actual Pouring Qty',
            'remarks' => 'Remarks',
            'scheme_value' => 'Scheme Value',
            'approved_value' => 'Approved Value',
            'application_status' => 'Application Status',
            'status_date' => 'Status Date',
            'status_by' => 'Status By',
            'status_remarks' => 'Status Remarks',
            'dcs_code' => 'Dcs Code',
            'bmc_code' => 'Bmc Code',
            'mcc_plant_code' => 'Mcc Plant Code',
            'plant_code' => 'Plant Code',
            'union_code' => 'Union Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
        ];
    }
}
