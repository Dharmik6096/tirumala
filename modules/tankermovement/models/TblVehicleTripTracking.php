<?php

namespace app\modules\tankermovement\models;

use Yii;

class TblVehicleTripTracking extends \app\models\ChildModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_trip_tracking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'plant_code', 'trip_code', 'vehicle_code', 'trip_date', 'trip_status', 'trip_sub_status', 'sub_status_time', 'remarks', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'visibility_status', 'module_code', 'module_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_trip_tracking_code' => Yii::t('app', 'Vehicle Trip Tracking Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'trip_code' => Yii::t('app', 'Trip'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'trip_date' => Yii::t('app', 'Trip Date'),
            'trip_status' => Yii::t('app', 'Trip Status'),
            'trip_sub_status' => Yii::t('app', 'Trip Sub Status'),
            'sub_status_time' => Yii::t('app', 'Sub Status Time'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
}
?>