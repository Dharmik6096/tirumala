<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblCustomerMaster;
use yii\helpers\ArrayHelper;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * This is the model class for table "tbl_customer_deactive".
 *
 * @property string $customer_deactive_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $customer_code
 * @property string $customer_type
 * @property string $from_date
 * @property string $to_date
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property integer $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $response_datetime
 */
class TblCustomerDeactive extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_customer_deactive';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'from_date'], 'required'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'picked_datetime', 'response_datetime'], 'safe'],
                [['remarks'], 'string'],
                [['originating_type', 'data_post_status'], 'integer'],
                [['customer_deactive_code', 'bmc_code'], 'string', 'max' => 12],
                [['union_code'], 'string', 'max' => 3],
                [['plant_code', 'mcc_plant_code'], 'string', 'max' => 6],
                [['customer_code', 'customer_type'], 'string', 'max' => 20],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
                [['resp_status', 'resp_desc'], 'string', 'max' => 255],
                [['to_date'], 'required', 'on' => ['activeCustomer']],
                [['from_date'], 'validateFromDate', 'except' => ['activeCustomer']],
                [['to_date'], 'validateToRange', 'on' => ['activeCustomer']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'customer_deactive_code' => Yii::t('app', 'Customer Deactive Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_code' => Yii::t('app', 'Customer'),
            'customer_type' => Yii::t('app', 'Type'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function validateFromDate($attribute, $params) {
        $existDCS = $this->find()
                ->where(['customer_code' => $this->customer_code])
                ->andfilterWhere(['!=', 'customer_deactive_code', $this->customer_deactive_code])
                ->andWhere(['IS', 'to_date', NULL])
                ->one();
        if (!empty($existDCS)) {
            $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'Customer') . ' Is Already Deactivated.'));
            return false;
        }
        $dateData = $this->find()
                ->where('customer_code=\'' . $this->customer_code . '\'')
                ->andWhere('((\'' . $this->from_date . '\'  between from_date and to_date))')
                ->andfilterWhere(['!=', 'customer_deactive_code', $this->customer_deactive_code])
                ->all();
        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
    }

    public function validateToRange($attribute, $params) {
        $fromDate = date('Y-m-d', strtotime($this->from_date));
        $toDate = date('Y-m-d', strtotime($this->to_date));
        if ($fromDate == $toDate || $fromDate > $toDate) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }

        $dateData = $this->find()
                ->where('customer_code=\'' . $this->customer_code . '\'')
                ->andWhere('((\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))')
                ->andfilterWhere(['!=', 'customer_deactive_code', $this->customer_deactive_code])
                ->all();
        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
    }

    public function getDeactiveRecords($checkStatus = true, $data = '') {
        $date = date('Y-m-d');
        $query = $this->find()
                ->where(['<=', 'from_date', $date])
                ->andWhere(['or', ['>=', 'to_date', $date], ['is', 'to_date', NULL]]);
        if ($checkStatus) {
            $query->andWhere(['or', ['data_post_status' => 0], ['is', 'data_post_status', NULL]]);
        }
        if (!empty($data) && (!empty($data['organization_code']) && !empty($data['organization_type']))) {
            if ($data['organization_type'] == 'MCC') {
                $query->andWhere(['mcc_plant_code' => $data['organization_code']]);
            }
            if ($data['organization_type'] == 'BMC') {
                $query->andWhere(['bmc_code' => $data['organization_code']]);
            }
            if ($data['organization_type'] == 'VLC') {
                $query->andWhere(['customer_code' => $data['organization_code']]);
            }
        }
        $data = $query->orderBy(['customer_deactive_code' => SORT_ASC])
                ->all();
        return $data;
    }

    public function getActiveRecords() {
        $date = date('Y-m-d');
        return $query = $this->find()
                ->where(['data_post_status' => 2])
                ->andWhere(['<', 'to_date', $date])
                ->orderBy(['customer_deactive_code' => SORT_ASC])
                ->all();
    }

    public function updateFileStatus($value, $status) {
        return $this->updateAll(['data_post_status' => $status, 'picked_datetime' => date('Y-m-d H:i:s')], ['customer_deactive_code' => $value]);
    }

}
