<?php

namespace app\modules\payment\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\transporter\models\TblTransporter;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\verification\models\TblVerification;

/**
 * This is the model class for table "tbl_transporter_payment".
 *
 * @property integer $transporter_payment_code
 * @property string $transporter_code
 * @property integer $total_vehicle
 * @property string $coll_qty
 * @property string $coll_kg_fat
 * @property string $coll_kg_snf
 * @property string $disp_qty
 * @property string $disp_kg_fat
 * @property string $disp_kg_snf
 * @property string $rec_qty
 * @property string $rec_kg_fat
 * @property string $rec_kg_snf
 * @property string $cd_qty_diff
 * @property string $cd_kg_fat_diff
 * @property string $cd_kg_snf_diff
 * @property string $rd_qty_diff
 * @property string $rd_kg_fat_diff
 * @property string $rd_kg_snf_diff
 * @property integer $no_of_days
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $adjust_amount
 * @property string $net_amount
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $delete_at
 * @property string $delete_by
 * @property integer $is_active
 */
class TblTransporterPayment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $transporter_payment_cycle, $otp_code;

    public static function tableName() {
        return 'tbl_transporter_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transporter_code', 'remarks', 'created_by', 'updated_by'], 'string'],
            [['total_vehicle', 'no_of_days'], 'integer'],
            [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'total_amount', 'total_deduction', 'final_amount', 'adjust_amount', 'net_amount'], 'number'],
            [['created_at', 'updated_at', 'from_date', 'to_date', 'bmc_code', 'union_code', 'transporter_payment_cycle'], 'safe'],
            [['status', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'is_verified', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'transporter_payment_code' => Yii::t('app', 'Transporter Payment Code'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'total_vehicle' => Yii::t('app', 'Total Vehicle'),
            'coll_qty' => Yii::t('app', 'Coll Qty'),
            'coll_kg_fat' => Yii::t('app', 'Coll Kg Fat'),
            'coll_kg_snf' => Yii::t('app', 'Coll Kg Snf'),
            'disp_qty' => Yii::t('app', 'Disp Qty'),
            'disp_kg_fat' => Yii::t('app', 'Disp Kg Fat'),
            'disp_kg_snf' => Yii::t('app', 'Disp Kg Snf'),
            'rec_qty' => Yii::t('app', 'Rec Qty'),
            'rec_kg_fat' => Yii::t('app', 'Rec Kg Fat'),
            'rec_kg_snf' => Yii::t('app', 'Rec Kg Snf'),
            'cd_qty_diff' => Yii::t('app', 'Cd Qty Diff'),
            'cd_kg_fat_diff' => Yii::t('app', 'Cd Kg Fat Diff'),
            'cd_kg_snf_diff' => Yii::t('app', 'Cd Kg Snf Diff'),
            'rd_qty_diff' => Yii::t('app', 'Rd Qty Diff'),
            'rd_kg_fat_diff' => Yii::t('app', 'Rd Kg Fat Diff'),
            'rd_kg_snf_diff' => Yii::t('app', 'Rd Kg Snf Diff'),
            'no_of_days' => Yii::t('app', 'No Of Days'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'adjust_amount' => Yii::t('app', 'Adjust Amount'),
            'net_amount' => Yii::t('app', 'Net Amount'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    public function vehicleDetail($model) {
        $bmc_code = $model->bmc_code;
        $transporter_code = $model->transporter_code;
        $from_date = date('Y-m-d', strtotime($model->from_date));
        $to_date = date('Y-m-d', strtotime($model->to_date));
        $result = \Yii::$app->db->createCommand("{CALL [sp_vehicle_summary](:bmc_code,:transporter_code,:from_date,:to_date)}")
                ->bindValue(':bmc_code', $bmc_code)
                ->bindValue(':transporter_code', $transporter_code)
                ->bindValue(':from_date', $from_date)
                ->bindValue(':to_date', $to_date);
        $query = $result->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'sort' => [
                'defaultOrder' => ['vehicle_code' => SORT_ASC],
                'attributes' => [
                    'vehicle_code',
                    'coll_qty',
                    'coll_kg_fat',
                    'coll_kg_snf',
                    'disp_qty',
                    'disp_kg_fat',
                    'rec_qty',
                    'rec_kg_fat',
                    'rec_kg_snf',
                    'cd_qty_diff',
                    'cd_kg_fat_diff',
                    'cd_kg_snf_diff',
                    'rd_qty_diff',
                    'rd_kg_fat_diff',
                    'rd_kg_snf_diff',
                    'no_of_days',
                    'total_amount',
                    'total_deduction',
                    'final_amount',
                ],
            ],
        ]);
        return $dataProvider;
    }

    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function existData($model) {
        $data = $this->find()
                ->where(['status' => 'processed'])
                ->andWhere(['transporter_code' => $model->transporter_code])
                ->andFilterWhere(['or', ['between', 'from_date', $model->from_date, $model->to_date], ['between', 'to_date', $model->from_date, $model->to_date]])
                ->all();
        if (!empty($data)) {
            return $data;
        } else {
            return [];
        }
    }

    public function transporterPaymentCycles($union_code) {
        return \yii\helpers\ArrayHelper::map($this->find()->select(['from_date', 'to_date', 'transporter_payment_code'])->where(['union_code' => $union_code, 'status' => ['processed', 'rejected']])->orderBy('from_date ASC')->distinct()->all(), function($model) {
                    return $model['transporter_payment_code'];
                }, function($model) {
                    return Yii::$app->controls->view_date($model['from_date']) . ' to ' . Yii::$app->controls->view_date($model['to_date']);
                });
    }

    public function getVerifiedBank() {
        return $this->hasOne(TblVerification::className(), ['module_id' => 'transporter_code'])
                        ->where(['module_name' => 'TblTransporter', 'module_field' => 'bank_account_no', 'status' => 1, 'is_verified' => 1]);
    }

    public function getRejectedBank() {
        return $this->hasOne(TblVerification::className(), ['module_id' => 'transporter_code'])
                        ->where(['module_name' => 'TblTransporter', 'module_field' => 'bank_account_no', 'status' => 2, 'is_verified' => 1]);
    }

}
