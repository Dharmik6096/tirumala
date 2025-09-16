<?php

namespace app\modules\veterinary\models;

use app\models\ChildModel;
use app\modules\globalmaster\models\TblUnits;
use Yii;

/**
 * This is the model class for table "tbl_treatment_details".
 *
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
 */
class TblTreatmentDetails extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_treatment_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['animal_treatment_request_id', 'diagnosis_detail_id', 'medicine_id'], 'required'],
            [['animal_treatment_request_id', 'diagnosis_detail_id', 'medicine_id', 'uom', 'originating_type'], 'integer'],
            [['qty'], 'number'],
            [['tran_datetime', 'created_at', 'updated_at'], 'safe'],
            [['ref_code', 'batch_no'], 'string', 'max' => 50],
            [['route', 'remarks'], 'string', 'max' => 500],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'treatment_id' => Yii::t('app', 'Treatment ID'),
            'animal_treatment_request_id' => Yii::t('app', 'Animal Treatment Request'),
            'diagnosis_detail_id' => Yii::t('app', 'Diagnosis Detail'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'medicine_id' => Yii::t('app', 'Medicine'),
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
        ];
    }

    public function getAnimalTreatmentRequestId() {
        return $this->hasOne(TblAnimalTreatmentRequest::className(), ['animal_treatment_request_id' => 'animal_treatment_request_id']);
    }

    public function getDiagnosisDetailId() {
        return $this->hasOne(TblDiagnosisDetails::className(), ['diagnosis_detail_id' => 'diagnosis_detail_id']);
    }

    public function getMedicineId() {
        return $this->hasOne(TblMedicineMaster::className(), ['medicine_id' => 'medicine_id']);
    }

    public function getUomDetail() {
        return $this->hasOne(TblUnits::className(), ['unit_code' => 'uom']);
    }
}
