<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_plant_dispatch".
 *
 * @property string $plant_dispatch_code
 * @property string $dispatch_date
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $document_date
 * @property string $document_no
 * @property string $status
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblPlantDispatch extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['plant_dispatch_code'], 'required'],
            [['union_code', 'mcc_plant_code', 'plant_code', 'document_no', 'document_date', 'dispatch_date'], 'required'],
            [['dispatch_date', 'document_date', 'created_at', 'updated_at', 'remarks', 'sap_batch_no'], 'safe'],
            [['plant_dispatch_code', 'union_code', 'mcc_plant_code', 'plant_code', 'document_no'], 'safe'],
            [['originating_type', 'status', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['sap_batch_no'], 'unique', 'targetAttribute' => ['sap_batch_no', 'plant_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['status'], 'default', 'value' => '0'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'plant_dispatch_code' => Yii::t('app', 'Plant Dispatch Code'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'union_code' => Yii::t('app', 'Union'),
            'document_date' => Yii::t('app', 'Document Date'),
            'document_no' => Yii::t('app', 'Document No'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

}
