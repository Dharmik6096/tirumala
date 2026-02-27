<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\vsp\models\TblBillHeadDefault;
use app\modules\organisation\models\TblDcs;
use yii\helpers\ArrayHelper;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\vsp\models\TblVspBillHeadCriteriaApplicability;

/**
 * This is the model class for table "tbl_bill_head".
 *
 * @property string $bill_head_code
 * @property string $bill_head_name
 * @property integer $is_default
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property integer $is_disburse_allowed
 * @property integer $bill_head_type
 * @property string $general_formula_code
 * @property string $calculation_based_on
 */
class TblBillHead extends \app\models\ChildModel {

    public $plant_code, $mcc_plant_code, $bmc_code, $customer_type, $payment_cycle_code, $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_code', 'bill_head_name', 'bill_head_type', 'union_code', 'sequence_no', 'bill_head_for'], 'required', 'except' => ['dcsWiseHead']],
                [['bill_head_code', 'bill_head_name', 'created_by', 'updated_by', 'union_code', 'general_formula_code'], 'string'],
                [['is_default', 'is_active', 'is_disburse_allowed', 'bill_head_type', 'sequence_no'], 'integer'],
                [['created_at', 'updated_at', 'general_formula', 'default_bill_head_code', 'calculation_based_on', 'is_hold', 'payment_cycle_type', 'milk_type_code'], 'safe'],
                [['is_active'], 'default', 'value' => '1'],
                [['is_disburse_allowed'], 'default', 'value' => '1'],
                [['is_default', 'has_slab', 'is_hold'], 'default', 'value' => '0'],
                [['payment_cycle_type'], 'default', 'value' => 'consecutive'],
                ['default_bill_head_code', 'unique', 'targetAttribute' => ['default_bill_head_code', 'union_code', 'bill_head_for'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Default Bill Head Type has already been taken.')],
                ['default_bill_head_code', 'required', 'when' => function ($model) {
                    return $model->is_default == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblbillhead-is_default').is(':checked'); 
          }"],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'bill_head_for', 'has_slab', 'sap_seq_no'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'customer_type', 'payment_cycle_code', 'to_date', 'is_reserved'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'to_date', 'bill_head_for', 'customer_type'], 'required', 'on' => ['dcsWiseHead']],
//            ['customer_type', 'required', 'when' => function ($model) {
//                    return $model->bill_head_for != 'MEMBER';
//                }, 'whenClient' => "function (attribute, value) { 
//              return $('#tblbillhead-bill_head_for').val()!='MEMBER'; 
//          }", 'on' => ['dcsWiseHead']],
                [['is_reserved'], 'default', 'value' => '0'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'bill_head_name' => Yii::t('app', 'Head Name'),
            'is_default' => Yii::t('app', 'Is Default'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
            'is_disburse_allowed' => Yii::t('app', 'Is Disburse Allowed'),
            'bill_head_type' => Yii::t('app', 'Bill Head Type'),
            'default_bill_head_code' => Yii::t('app', 'Default Bill Head Type'),
            'general_formula_code' => Yii::t('app', 'Formula'),
            'sequence_no' => Yii::t('app', 'Sequence No.'),
            'bill_head_for' => Yii::t('app', 'Head For'),
            'calculation_based_on' => Yii::t('app', 'Calculation Based On'),
            'is_hold' => Yii::t('app', 'Is Hold'),
            'payment_cycle_type' => Yii::t('app', 'Payment Cycle Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'is_reserved' => Yii::t('app', 'Is Reserved'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblBillHeadQuery the active query used by this AR class.
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBillHeadCode() {
        return $this->hasOne(TblBillHeadApplicability::className(), ['bill_head_code' => 'bill_head_code']);
    }

    public function getDefaultBillHeadCode() {
        return $this->hasOne(TblBillHeadDefault::className(), ['default_bill_head_code' => 'default_bill_head_code']);
    }

    public function getAllBillHead($society = '', $union = '') {
        $query = $this->find()->select('tbl_bill_head.bill_head_code,bill_head_name')->where(['tbl_bill_head.is_active' => 1]);
        $query->andWhere(['or', ['general_formula_code' => ''], ['general_formula_code' => null]]);
        if (!empty($union)) {
            $query->joinWith('defaultBillHeadCode');
            $query->andWhere(['or', ['is_default' => 0], ['bill_head_type' => 1]]);
            $query->andWhere(['union_code' => $union]);
        } else {
            $query->andWhere(['is_default' => 0]);
        }
        if (!empty($society)) {
            $query->innerJoinWith('billHeadCode')->andWhere(['dcs_code' => $society]);
        }
        return $query->all();
    }

    public function billHeadData($society = [], $union = []) {
        $list = $this->getAllBillHead($society, $union);
        return \yii\helpers\ArrayHelper::map($list, 'bill_head_code', 'bill_head_name');
    }

    public function billHeadType($bill_head_code) {
        return $this->find()->select(['bill_head_type'])->where(['bill_head_code' => $bill_head_code])->one();
    }

    public function billHeadTypeWise($union, $type, $code, $headFor) {
        $query = $this->find()
                ->innerJoinWith('billHeadCode as apl')
                ->where(['is_active' => 1, 'is_default' => 0, 'tbl_bill_head.bill_head_for' => $headFor, 'apl.union_code' => $union, 'apl.applicable_for' => $type, 'apl.applicable_code' => $code])
                ->andWhere(['or', ['general_formula_code' => ''], ['general_formula_code' => null]]);
        $list = $query->all();

        return \yii\helpers\ArrayHelper::map($list, 'bill_head_code', function($data) {
                    return isset($data->bill_head_type) ? $data->bill_head_name . ' (' . Yii::$app->dropdown->getRecords('calc_type')['data'][$data->bill_head_type] . ')' : $data->bill_head_name;
                });
    }

    public function getBillHead($model) {
        $query = $this->find()
                ->where(['is_active' => 1, 'union_code' => $model->union_code, 'bill_head_for' => $model->bill_head_for])
                ->andWhere(['IN', 'has_slab', ['0', NULL, '']]);
        return $list = $query->all();
    }

    public function getDcs($data) {
        if ($data['customer_type'] == 'DCS') {
            $dcs = new TblDcs();
            $query = $dcs->find()->where(['union_code' => $data['union_code'], 'plant_code' => $data['plant_code'], 'mcc_plant_code' => $data['mcc_plant_code'], 'bmc_code' => $data['bmc_code'], 'is_active' => 1]);
            if (Yii::$app->session->get('Dcs') !== '') {
                $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
            }
            return $dcsData = $query->all();
        } else {
            $customer = new TblCustomerMaster();
            $query = $customer->find()->where(['union_code' => $data['union_code'], 'plant_code' => $data['plant_code'], 'mcc_plant_code' => $data['mcc_plant_code'], 'bmc_code' => $data['bmc_code'], 'is_active' => 1, 'customer_type' => $data['customer_type']]);
            return $dcsData = $query->all();
        }
    }

    public function getApplicabiliytData($searchData, $code, $date) {
        $applicable = new TblBillHeadApplicability();
        $query = $applicable->find()
                        ->where(['union_code' => $searchData->union_code, 'bmc_code' => $searchData->bmc_code, 'applicable_code' => $code, 'applicable_for' => $searchData->customer_type, 'bill_head_for' => $searchData->bill_head_for])->all();
//                        ->andWhere(['<=', 'wef_date', $date])->all();

        return \yii\helpers\ArrayHelper::map($query, 'bill_head_code', 'bill_head_code');
//        return $list = $query->one();
    }

    public function getPaymentCycle() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'payment_cycle_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getCriteria($model) {
        $query = TblBillHead::find()
                ->select(['tbl_bill_head.bill_head_code', 'tbl_vsp_bill_head_criteria.criteria_name', 'tbl_vsp_bill_head_criteria.vsp_criteria_code'])
                ->innerJoin('tbl_vsp_bill_head_criteria', 'tbl_bill_head.bill_head_code = tbl_vsp_bill_head_criteria.bill_head_code')
                ->where(['tbl_bill_head.is_active' => 1, 'tbl_bill_head.union_code' => $model->union_code, 'tbl_bill_head.bill_head_for' => $model->bill_head_for,
        ]);

        return $query->asArray()->all();
    }

    public function getCriteriaApplicabiliytData($searchData, $code) {
        $applicable = new TblVspBillHeadCriteriaApplicability();
        $query = $applicable->find()
                        ->where(['union_code' => $searchData->union_code, 'bmc_code' => $searchData->bmc_code, 'applicable_code' => $code, 'applicable_for' => $searchData->customer_type, 'bill_head_for' => $searchData->bill_head_for])->all();

        return ArrayHelper::map($query, 'bill_head_code', 'bill_head_code');
    }

}
