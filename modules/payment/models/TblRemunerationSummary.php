<?php

namespace app\modules\payment\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_remuneration_summary".
 *
 * @property integer $remuneration_summary_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property integer $calculate_milk_recovey
 * @property integer $calculate_other_head
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblRemunerationSummary extends \app\models\ChildModel {

    public $p_bmc_code, $payment_cycle_code, $stop_payment_only = 0;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_remuneration_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'status', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'p_bmc_code'], 'safe'],
                [['from_datetime', 'to_datetime', 'created_at', 'updated_at', 'payment_cycle_code'], 'safe'],
                [['from_shift', 'to_shift', 'calculate_milk_recovey', 'calculate_other_head', 'originating_type'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'from_datetime', 'to_datetime'], 'required'],
                [['bmc_code'], 'required', 'on' => ['processpayment', 'processpaymentstop']],
                [['payment_cycle_code'], 'required', 'on' => ['processpaymentstop']],
//            [['from_datetime'], function ($attribute, $params) {
//                    return Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_datetime', 'to_datetime', 30, '!=', 'Date Difference must be 30 Days.');
//                }, 'skipOnError' => true, 'on' => 'processpayment'],
            [['from_datetime'], 'ValidateDate', 'skipOnError' => true, 'on' => 'processpayment'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'remuneration_summary_code' => Yii::t('app', 'Remuneration Summary Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'from_datetime' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'calculate_milk_recovey' => Yii::t('app', 'Calculate Milk Recovey ?'),
            'calculate_other_head' => Yii::t('app', 'Calculate Other Head ?'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'p_bmc_code' => Yii::t('app', 'BMC'),
        ];
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function ValidateDate($attribute, $params, $process_alert = FALSE) {
        $from_date = date('Y-m-d', strtotime($this->from_datetime));
        $to_date = date('Y-m-d', strtotime($this->to_datetime));
        if ($to_date < $from_date) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
            return false;
        }
        if (\Yii::$app->session->get('eiplCode') == 'MMD') {
            $first_day_month = date('Y-m-01', strtotime($this->from_datetime));
            $last_day_month = date('Y-m-t', strtotime($this->from_datetime));
            if ($from_date != $first_day_month || $to_date != $last_day_month) {
                $this->addError($attribute, Yii::t('app/validation', 'From Date and To Date must be Start Date and End Date of month.'));
                return false;
            }
        }

        $query = TblRemunerationSummary::find()
                ->where(['union_code' => $this->union_code, 'bmc_code' => $this->bmc_code]);
        if ($process_alert === TRUE) {
            $query->andWhere(['in', 'status', ['generated', 'processed']]);
        } else {
            $query->andWhere(['not in', 'status', ['generated', 'processed']]);
        }
        $count = $query->andWhere(['or',
                        ['or',
                            ['between', 'CAST(from_datetime as date)', $from_date, $to_date],
                            ['between', 'CAST(to_datetime as date)', $from_date, $to_date]
                    ],
                        ['or',
                        "'$from_date' BETWEEN CAST([from_datetime] as date) AND CAST([to_datetime] as date)",
                        "'$to_date' BETWEEN CAST([from_datetime] as date) AND CAST([to_datetime] as date)"
            ]])->count();
        if ($process_alert === TRUE) {
            return $count > 0 ? FALSE : TRUE;
        } else {
            if ($count > 0) {
                $this->addError($attribute, "Payment already done/locked.");
                return FALSE;
            } else {
                $pending_disburse = $this->find()
                                ->where(['status' => ['generated', 'processed'], 'bmc_code' => $this->bmc_code])
                                ->andWhere(['or',
                                        ['or',
                                            ['NOT BETWEEN', 'CAST(from_datetime as date)', $from_date, $to_date],
                                            ['NOT BETWEEN', 'CAST(to_datetime as date)', $from_date, $to_date]
                                    ],
                                        ['or',
                                        "'$from_date' NOT BETWEEN CAST([from_datetime] as date) AND CAST([to_datetime] as date)",
                                        "'$to_date' NOT BETWEEN CAST([from_datetime] as date) AND CAST([to_datetime] as date)"
                            ]])->one();
                if (!empty($pending_disburse)) {
                    $from_date = date('d-m-Y', strtotime($pending_disburse->from_datetime));
                    $to_date = date('d-m-Y', strtotime($pending_disburse->to_datetime));
                    $this->addError($attribute, Yii::t('app', "Please first disburse payment of $from_date to $to_date ."));
                    return FALSE;
                }
                $unlock_cnt = TblPaymentCycleApplicability::find()->select(['status'])
                        ->where(['union_code' => $this->union_code,
                            'applicable_code' => $this->bmc_code,
                            'applicable_for' => 'BMC',
                            'applicable_type' => 'DCS'])
                        ->andWhere(['or',
                                ['or',
                                    ['between', 'CAST(from_date as date)', $from_date, $to_date],
                                    ['between', 'CAST(to_date as date)', $from_date, $to_date]
                            ],
                                ['or',
                                "'$from_date' BETWEEN CAST([from_date] as date) AND CAST([to_date] as date)",
                                "'$to_date' BETWEEN CAST([from_date] as date) AND CAST([to_date] as date)"
                    ]])
                        ->andWhere(['or', ['data_lock_member' => 0], ['data_lock_bmc' => 0]])
                        ->count();
                if ($unlock_cnt > 0) {
                    $this->addError($attribute, "Please Lock Data of all payment cycle included.");
                    return FALSE;
                }
            }
        }
    }

    public function RemunerationPaymentCycle($union_code, $bmc_code) {
        $data = $this->find()->select(['from_datetime', 'to_datetime'])
                        ->where(['union_code' => $union_code, 'bmc_code' => $bmc_code])
                        ->andWhere(['in', 'status', ['locked']])->asArray()->all();

        return ArrayHelper::map($data, function($data) {
                    return $data['from_datetime'] . '#' . $data['to_datetime'];
                }, function($data) {
                    return date('d-m-Y', strtotime($data['from_datetime'])) . ' To ' . date('d-m-Y', strtotime($data['to_datetime']));
                });
    }

}
