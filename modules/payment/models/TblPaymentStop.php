<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_payment_stop".
 *
 * @property integer $payment_stop_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property integer $payment_cycle_code
 * @property string $from_datetime
 * @property string $to_datetime
 * @property string $payment_type
 * @property string $stop_reason
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPaymentStop extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_stop';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_cycle_code', 'originating_type'], 'safe'],
                [['from_datetime', 'to_datetime', 'created_at', 'updated_at'], 'safe'],
                [['union_code'], 'string', 'max' => 3],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'string', 'max' => 12],
                [['customer_type', 'customer_code'], 'string', 'max' => 20],
                [['payment_type'], 'string', 'max' => 50],
                [['stop_reason'], 'string', 'max' => 100],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_stop_code' => Yii::t('app', 'Payment Stop Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'from_datetime' => Yii::t('app', 'From Datetime'),
            'to_datetime' => Yii::t('app', 'To Datetime'),
            'payment_type' => Yii::t('app', 'Payment Type'),
            'stop_reason' => Yii::t('app', 'Stop Reason'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getStatusStop() {
        $data = $this->find()->where(['bmc_code' => $this->bmc_code])->one();
        if (!empty($data)) {
            return 'First Process Stop Payment of ' . Yii::$app->controls->view_date($data->from_datetime) . ' to ' . Yii::$app->controls->view_date($data->to_datetime) . ' .';
        }
        return '';
    }

}
