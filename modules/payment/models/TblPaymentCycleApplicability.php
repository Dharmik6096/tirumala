<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\payment\models\TblPaymentCycle;

/**
 * This is the model class for table "tbl_payment_cycle_applicability".
 *
 * @property integer $payment_cycle_applicabilty_code
 * @property integer $payment_cycle_code
 * @property string $from_date
 * @property string $to_date
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $union_code
 * @property integer $data_lock_bmc
 * @property integer $data_lock_member
 * @property integer $billing_lock_bmc
 * @property integer $billing_lock_member
 * @property integer $sync_lock_bmc
 * @property integer $sync_lock_member
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPaymentCycleApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_cycle_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['applicable_code', 'applicable_type'], 'required'],
                [['payment_cycle_code', 'data_lock_bmc', 'data_lock_member', 'billing_lock_bmc', 'billing_lock_member', 'sync_lock_bmc', 'sync_lock_member', 'originating_type'], 'safe'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'union_code'], 'safe'],
                [['applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by'], 'safe'],
                [['data_lock_bmc', 'data_lock_member', 'billing_lock_bmc', 'billing_lock_member', 'sync_lock_bmc', 'sync_lock_member'], 'default', 'value' => 0],
//                [['applicable_code'], 'validatePaymentCycle', 'skipOnEmpty' => false], //Comment as Set Validation from DB Side: Hardik
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'data_lock_bmc' => Yii::t('app', 'Data Lock Bmc'),
            'data_lock_member' => Yii::t('app', 'Data Lock Member'),
            'billing_lock_bmc' => Yii::t('app', 'Billing Lock Bmc'),
            'billing_lock_member' => Yii::t('app', 'Billing Lock Member'),
            'sync_lock_bmc' => Yii::t('app', 'Sync Lock Bmc'),
            'sync_lock_member' => Yii::t('app', 'Sync Lock Member'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_type']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getUnionPaymentCycle() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function setApplicableData() {
        $this->applicable_for = 'BMC';
        if (!empty($this->unionPaymentCycle)) {
            $this->from_date = $this->unionPaymentCycle->from_date;
            $this->to_date = $this->unionPaymentCycle->to_date;
        }
    }

    public function getName($applicableFor) {
        if ($applicableFor == 'PLANT') {
            return Yii::$app->general->getforeignkey($this->plantCode, 'name');
        } else if ($applicableFor == 'MCC') {
            return Yii::$app->general->getforeignkey($this->mccPlantCode, 'name');
        } else if ($applicableFor == 'BMC') {
            return Yii::$app->general->getforeignkey($this->bmcCode, 'bmc_name');
        } else if ($applicableFor == 'DCS') {
            return Yii::$app->general->getforeignkey($this->dcsCode, 'dcs_name');
        } else {
            return Yii::$app->general->getforeignkey($this->mainCustomerCode, 'customer_name');
        }
    }

    public function validatePaymentCycle($attribute, $params) {
        return $this->validateData();
    }

    public function validateData($returnCodes = false) {
        $query = $this->find()
                ->where(['applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for, 'applicable_type' => $this->applicable_type])
                ->andWhere('((\'' . $this->from_date . '\'  between from_date and to_date) OR (\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))');
        if (!empty($this->payment_cycle_applicabilty_code)) {
            $query->andWhere(['!=', 'payment_cycle_applicabilty_code', $this->payment_cycle_applicabilty_code]);
        }

        $data = $query->all();
        $applicable_code = [];
        $message = [];
        for ($i = 0; $i < count($data); $i++) {
            $mesageVal = $data[$i]->getName($data[$i]->applicable_for) . ' - ' . Yii::$app->general->getforeignkey($data[$i]->customerType, 'customer_desc') . '(' . Yii::$app->controls->view_date($data[$i]->from_date) . ' to ' . Yii::$app->controls->view_date($data[$i]->to_date) . ')';
            $message[$mesageVal] = $mesageVal;
            $applicable_code[] = $data[$i]->applicable_code;
        }
        if (count($message) > 0) {
            $messagestring = 'Following are the current applicabilities.<br/>' . implode('<br/>', $message);
            $this->addError('applicable_code', $messagestring);
            if ($returnCodes) {
                return $applicable_code;
            }
            return false;
        }
        if ($returnCodes) {
            return $applicable_code;
        }
        return true;
    }

    public function checkDelete() {
        return true; //$vendor ? false : true;
    }

    public function getData() {
        return $this->find()
                        ->where(['payment_cycle_applicabilty_code' => $this->payment_cycle_applicabilty_code])
                        ->one();
    }

    public function paymentCycles($union_code, $bmc, $type, $for, $where) {
        return \yii\helpers\ArrayHelper::map($this->find()->select(['from_date', 'to_date', 'payment_cycle_code'])->where(['union_code' => $union_code, 'applicable_code' => $bmc, 'applicable_type' => $type, 'applicable_for' => $for])->andWhere($where)->andWhere(['<', 'from_date', date('Y-m-d')])->orderBy('from_date ASC')->distinct()->all(), function($model) {
                    return $model['payment_cycle_code'];
                }, function($model) {
                    return Yii::$app->controls->view_date($model['from_date']) . ' to ' . Yii::$app->controls->view_date($model['to_date']);
                });
    }

    public function getApplicablePaymentCycle($date) {
        return $this->find()
                        ->where(['applicable_type' => $this->applicable_type, 'applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for])
                        ->andWhere('\'' . $date . '\' between cast(from_date as date) and cast(to_date as date)')
                        ->one();
    }

    public function getStatusCount($where = []) {
        return $this->find()
                        ->where(['payment_cycle_code' => $this->payment_cycle_code, 'applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for, 'applicable_type' => $this->applicable_type])
                        ->andWhere($where)
                        ->count();
    }

}
