<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_location_detail_history".
 *
 * @property integer $id
 * @property string $dcs_location_detail_code
 * @property string $date_time_of_location
 * @property string $latitude
 * @property string $longitude
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $received_timestamp
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblDcsLocationDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_location_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'latitude', 'longitude', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'dcs_location_detail_code', 'originating_type', 'date_time_of_location', 'received_timestamp', 'created_at', 'updated_at', 'history_created_at', 'history_created_by', 'operation_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'dcs_location_detail_code' => Yii::t('app', 'Dcs Location Detail Code'),
            'date_time_of_location' => Yii::t('app', 'Date Time Of Location'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'received_timestamp' => Yii::t('app', 'Received Timestamp'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
