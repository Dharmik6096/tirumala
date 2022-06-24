<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_extra_km_daywise_history".
 *
 * @property integer $id
 * @property integer $extra_km_code
 * @property string $vehicle_code
 * @property string $transporter_code
 * @property string $date
 * @property string $extra_kms
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblVehicleExtraKmDaywiseHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_extra_km_daywise_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['extra_km_code', 'originating_type', 'rate'], 'safe'],
                [['vehicle_code', 'transporter_code', 'created_by', 'updated_by', 'union_code', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['extra_kms'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'extra_km_code' => Yii::t('app', 'Extra Km Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'date' => Yii::t('app', 'Date'),
            'extra_kms' => Yii::t('app', 'Extra Kms'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
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

}
