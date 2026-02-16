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
use app\modules\syncutility\models\TblSentbox;

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

    public $check_for, $data_status;

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
                [['payment_cycle_code', 'data_lock_bmc', 'data_lock_member', 'billing_lock_bmc', 'billing_lock_member', 'sync_lock_bmc', 'sync_lock_member', 'originating_type', 'process_lock_bmc','process_lock_member'], 'safe'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'union_code'], 'safe'],
                [['applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['data_lock_bmc', 'data_lock_member', 'billing_lock_bmc', 'billing_lock_member', 'sync_lock_bmc', 'sync_lock_member'], 'default', 'value' => 0],
                [['applicable_code'], 'validatePaymentCycle', 'skipOnEmpty' => false, 'except' => ['lockUnlock', 'processLock']], //Comment as Set Validation from DB Side: Hardik //-> 21/02/2023 uncoment for add range conflict check due to issue in credit check in product sale : Seema
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
            'process_lock_bmc' => Yii::t('app', 'Process Lock BMC'),
            'process_lock_member' => Yii::t('app', 'Process Lock Member'),
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
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_type', 'union_code' => 'union_code']);
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
        $controls = [];
        $controls['applicable_code'] = is_array($this->applicable_code) ? ',' . implode(',', $this->applicable_code) : $this->applicable_code;
        $controls['applicable_for'] = $this->applicable_for;
        $controls['applicable_type'] = is_array($this->applicable_type) ? ',' . implode(',', $this->applicable_type) : $this->applicable_type;
        $controls['from_date'] = $this->from_date;
        $controls['to_date'] = $this->to_date;

        $sp_name = 'portal_sp_conflict_payment_cycle_applicability';
        $data = \Yii::$app->general->getSpData($sp_name, $controls);

//        $query = $this->find()
//                ->where(['applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for, 'applicable_type' => $this->applicable_type])
//                ->andWhere('((\'' . $this->from_date . '\'  between from_date and to_date) OR (\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))');
//        if (!empty($this->payment_cycle_applicabilty_code)) {
//            $query->andWhere(['!=', 'payment_cycle_applicabilty_code', $this->payment_cycle_applicabilty_code]);
//        }
//
//        $data = $query->all();
        $applicable_code = [];
        $message = [];
        if (!empty($data)) {
            for ($i = 0; $i < count($data); $i++) {
                $mesageVal = $data[$i]['name'] . ' - ' . $data[$i]['applicable_type'] . '(' . Yii::$app->controls->view_date($data[$i]['from_date']) . ' to ' . Yii::$app->controls->view_date($data[$i]['to_date']) . ')';
                $message[$mesageVal] = $mesageVal;
                $applicable_code[] = $data[$i]['applicable_code'];
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
    }

    public function checkDelete() {
        return true; //$vendor ? false : true;
    }

    public function getData() {
        return $this->find()
                        ->where(['payment_cycle_applicabilty_code' => $this->payment_cycle_applicabilty_code])
                        ->one();
    }

    public function paymentCycles($union_code, $bmc, $type, $for, $where, $member_billing_lock_check = '0', $ignoreTypeCheck = false) {
        $query = $this->find()->select(['from_date', 'to_date', 'payment_cycle_code'])->distinct()
                ->where(['union_code' => $union_code, 'applicable_code' => $bmc, 'applicable_for' => $for])->andWhere($where)
                ->andWhere(['<=', 'cast(from_date as date)', date('Y-m-d')]);

        if (empty($ignoreTypeCheck) || !empty($type)) {
            $query->andWhere(['applicable_type' => $type]);
        }
        if ($type == 'DCS' && $member_billing_lock_check == '1') {
            $check = Yii::$app->general->getConfigMapping('member_billing_lock_check', $bmc, 'BMC');
            if ($check == '' || $check == '1') {
                $query->andWhere(['billing_lock_member' => 1]);
            }
        }
        $data = $query->orderBy('from_date DESC')->all();
        return \yii\helpers\ArrayHelper::map($data, function($model) {
                    return $model['payment_cycle_code'];
                }, function($model) {
                    return Yii::$app->controls->view_date($model['from_date']) . ' to ' . Yii::$app->controls->view_date($model['to_date']);
                });
    }

    public function getApplicablePaymentCycle($date) {
        return $this->find()
                        ->where('\'' . $date . '\' between cast(from_date as date) and cast(to_date as date)')
                        ->andWhere(['applicable_code' => $this->applicable_code, 'applicable_type' => $this->applicable_type, 'applicable_for' => $this->applicable_for])
                        ->one();
    }

    public function getStatusCount($where = []) {
        return $this->find()
                        ->where(['payment_cycle_code' => $this->payment_cycle_code, 'applicable_code' => $this->applicable_code, 'applicable_for' => $this->applicable_for, 'applicable_type' => $this->applicable_type])
                        ->andWhere($where)
                        ->count();
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $applicableFor = $this->applicable_for;
        if ($applicableFor == 'DCS') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->applicable_code);
        } else if ($applicableFor == 'MCC') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->applicable_code);
        } else {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->applicable_code);
        }
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function afterDelete() {
        $sentboxArray = [];
        $applicableFor = $this->applicable_for;
        if ($applicableFor == 'DCS') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->applicable_code);
        } else if ($applicableFor == 'MCC') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->applicable_code);
        } else {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->applicable_code);
        }
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function getPaymentCycle($from_date, $to_date, $applicable_code, $applicable_type, $applicable_for) {
        $payment_cycle = $this->find()->select(['tbl_payment_cycle_applicability.payment_cycle_code', 'tbl_payment_cycle.from_shift', 'tbl_payment_cycle.to_shift'])
                        ->innerJoin('tbl_payment_cycle', 'tbl_payment_cycle.payment_cycle_code = tbl_payment_cycle_applicability.payment_cycle_code')
                        ->where(['applicable_code' => $applicable_code, 'applicable_type' => $applicable_type, 'applicable_for' => $applicable_for])
                        ->andWhere(['and', ['>=', ' cast(tbl_payment_cycle_applicability.from_date as date)', $from_date], ['<=', ' cast(tbl_payment_cycle_applicability.to_date as date)', $to_date]])
                        ->orderBy('tbl_payment_cycle_applicability.payment_cycle_applicabilty_code DESC')
                        ->asArray()->one();
        if (!empty($payment_cycle)) {
            return [
                'payment_cycle_code' => $payment_cycle['payment_cycle_code'],
                'from_shift' => $payment_cycle['from_shift'],
                'to_shift' => $payment_cycle['to_shift']
            ];
        }
        return [];
    }

}
