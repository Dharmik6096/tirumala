<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\dcsoperation\models\TblMember;
use app\modules\welfarescheme\models\TblSchemeMaster;
use app\modules\welfarescheme\models\TblSchemeDocumentMaster;

/**
 * This is the model class for table "tbl_scheme_application".
 *
 * @property integer $application_id
 * @property integer $scheme_id
 * @property string $customer_code
 * @property string $customer_type
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
class TblSchemeApplication extends \app\models\ChildModel {

    public $customer_name, $ex_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_application';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['application_status'], 'default', 'value' => 'pending'],
                [['scheme_id', 'customer_type', 'customer_code', 'application_date', 'plant_code', 'mcc_plant_code', 'bmc_code', 'union_code', 'ex_code'], 'required', 'on' => 'add_application'],
                [['scheme_id', 'originating_type'], 'integer'],
                [['application_date', 'status_date', 'created_at', 'updated_at', 'ex_code'], 'safe'],
                [['min_pouring_day', 'min_pouring_qty', 'actual_pouring_day', 'actual_pouring_qty', 'scheme_value', 'approved_value'], 'number'],
                [['customer_code', 'customer_type', 'application_status', 'status_by'], 'string', 'max' => 20],
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
    public function attributeLabels() {
        return [
            'application_id' => 'Application ID',
            'scheme_id' => 'Scheme ID',
            'customer_code' => 'Customer Code',
            'customer_type' => 'Customer Type',
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function getMainDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'bmc_code' => $this->bmc_code, 'customer_code_ex' => $this->ex_code]);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }

    public function getSchemeDetail() {
        $application_date = date('Y-m-d', strtotime($this->application_date));
        $scheme_detail = \Yii::$app->general->getSpData('sp_welfarescheme_customer_detail', [$this->customer_code, $this->customer_type, $this->scheme_id, $application_date]);
        if (!empty($scheme_detail)) {
            $scheme_detail = $scheme_detail[0];
        } else {
            $scheme_detail = [];
            $scheme_detail['scheme_value'] = $scheme_detail['min_pouring_day'] = $scheme_detail['min_pouring_qty'] = $scheme_detail['p_qty'] = $scheme_detail['p_day'] = 0.00;
        }
        return $scheme_detail;
    }

}
