<?php

namespace app\modules\veterinary\models;

use Yii;

/**
 * This is the model class for table "tbl_treatment_details_history".
 *
 * @property integer $id
 * @property integer $treatment_id
 * @property integer $animal_treatment_request_id
 * @property integer $diagnosis_detail_id
 * @property string $ref_code
 * @property integer $medicine_id
 * @property string $batch_no
 * @property string $qty
 * @property integer $uom
 * @property string $route
 * @property string $remarks
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
class TblTreatmentDetailsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_treatment_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['treatment_id', 'animal_treatment_request_id', 'diagnosis_detail_id', 'ref_code', 'medicine_id', 'batch_no', 'qty', 'uom', 'route', 'remarks', 'tran_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'operation_type', 'history_created_at', 'history_created_by',], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'treatment_id' => Yii::t('app', 'Treatment ID'),
            'animal_treatment_request_id' => Yii::t('app', 'Animal Treatment Request ID'),
            'diagnosis_detail_id' => Yii::t('app', 'Diagnosis Detail ID'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'medicine_id' => Yii::t('app', 'Medicine ID'),
            'batch_no' => Yii::t('app', 'Batch No'),
            'qty' => Yii::t('app', 'Qty'),
            'uom' => Yii::t('app', 'Uom'),
            'route' => Yii::t('app', 'Route'),
            'remarks' => Yii::t('app', 'Remarks'),
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
