<?php

namespace app\modules\payment\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_vendor_payment_hold_release_summary".
 *
 * @property integer $vendor_payment_hold_release_summary_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $customer_type
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblVendorPaymentHoldReleaseSummary extends \yii\db\ActiveRecord
{
    public $payment_cycle_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vendor_payment_hold_release_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_payment_hold_release_summary_code','union_code','plant_code','mcc_plant_code','bmc_code','customer_type','from_datetime','from_shift','to_datetime','to_shift','status','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','payment_cycle_code'], 'safe'],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'customer_type', 'from_datetime', 'to_datetime'], 'required', 'on' => ['processpayment']],
            [['from_datetime'], 'ValidateDate', 'skipOnError' => true, 'on' => 'processpayment'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vendor_payment_hold_release_summary_code' => Yii::t('app', 'Vendor Payment Hold Release Summary Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'from_datetime' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
    public function ValidateDate($attribute, $params, $process_alert = FALSE) {
        $from_date = date('Y-m-d', strtotime($this->from_datetime));
        $to_date = date('Y-m-d', strtotime($this->to_datetime));
        if ($to_date < $from_date) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
            return false;
        }
        // if (\Yii::$app->session->get('eiplCode') == 'MMD') {
        //     $first_day_month = date('Y-m-01', strtotime($this->from_datetime));
        //     $last_day_month = date('Y-m-t', strtotime($this->from_datetime));
        //     if ($from_date != $first_day_month || $to_date != $last_day_month) {
        //         $this->addError($attribute, Yii::t('app/validation', 'From Date and To Date must be Start Date and End Date of month.'));
        //         return false;
        //     }
        // }
        $query = TblVendorPaymentHoldReleaseSummary::find()
                ->where(['union_code' => $this->union_code, 'bmc_code' => $this->bmc_code, 'customer_type' => $this->customer_type]);
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
                        ->where(['status' => ['generated', 'processed'], 'bmc_code' => $this->bmc_code, 'customer_type' => $this->customer_type])
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
                        'applicable_type' => $this->customer_type])
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

    public function HoldReleasePaymentCycle($union_code, $bmc_code) {
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
