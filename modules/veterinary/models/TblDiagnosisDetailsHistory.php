<?php

namespace app\modules\veterinary\models;

use Yii;

/**
 * This is the model class for table "tbl_diagnosis_details_history".
 *
 * @property integer $id
 * @property integer $diagnosis_detail_id
 * @property integer $animal_treatment_request_id
 * @property string $disease_id
 * @property integer $milking_status
 * @property string $milk_production
 * @property integer $symptom_id
 * @property string $remarks
 * @property string $lat_long
 * @property string $case_fee
 * @property string $bank_name
 * @property string $gateway
 * @property string $payment_mode
 * @property string $tran_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblDiagnosisDetailsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_diagnosis_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['diagnosis_detail_id', 'animal_treatment_request_id', 'disease_id', 'milking_status', 'milk_production', 'symptom_id', 'remarks', 'lat_long', 'case_fee', 'bank_name', 'gateway', 'payment_mode', 'tran_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'operation_type', 'history_created_at', 'history_created_by',], 'safe',],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'diagnosis_detail_id' => Yii::t('app', 'Diagnosis Detail ID'),
            'animal_treatment_request_id' => Yii::t('app', 'Animal Treatment Request ID'),
            'disease_id' => Yii::t('app', 'Disease ID'),
            'milking_status' => Yii::t('app', 'Milking Status'),
            'milk_production' => Yii::t('app', 'Milk Production'),
            'symptom_id' => Yii::t('app', 'Symptom ID'),
            'remarks' => Yii::t('app', 'Remarks'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'case_fee' => Yii::t('app', 'Case Fee'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'gateway' => Yii::t('app', 'Gateway'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'tran_datetime' => Yii::t('app', 'Tran Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
