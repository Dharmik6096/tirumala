<?php

namespace app\modules\clienterp\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_client_erp_api_log".
 *
 * @property integer $log_id
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $end_point
 * @property string $request_url
 * @property string $request_desc
 * @property string $txn_type
 * @property string $date1
 * @property string $date2
 * @property string $desc1
 * @property string $desc2
 * @property string $request_header
 * @property string $request_payload
 * @property string $response_payload
 * @property string $request_timestamp
 * @property string $response_timestamp
 * @property integer $status_code
 * @property string $status_type
 * @property string $status_response
 * @property string $status_message
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
class TblClientErpApiLog extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_client_erp_api_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code','plant_code','mcc_plant_code','bmc_code','end_point','request_url','request_desc','txn_type','date1','date2','desc1','desc2','request_header','request_payload','response_payload','request_timestamp','response_timestamp','status_code','status_type','status_response','status_message','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'end_point' => Yii::t('app', 'End Point'),
            'request_url' => Yii::t('app', 'Request Url'),
            'request_desc' => Yii::t('app', 'Request Desc'),
            'txn_type' => Yii::t('app', 'Txn Type'),
            'date1' => Yii::t('app', 'Date1'),
            'date2' => Yii::t('app', 'Date2'),
            'desc1' => Yii::t('app', 'Desc1'),
            'desc2' => Yii::t('app', 'Desc2'),
            'request_header' => Yii::t('app', 'Request Header'),
            'request_payload' => Yii::t('app', 'Request Payload'),
            'response_payload' => Yii::t('app', 'Response Payload'),
            'request_timestamp' => Yii::t('app', 'Request Timestamp'),
            'response_timestamp' => Yii::t('app', 'Response Timestamp'),
            'status_code' => Yii::t('app', 'Status Code'),
            'status_type' => Yii::t('app', 'Status Type'),
            'status_response' => Yii::t('app', 'Status Response'),
            'status_message' => Yii::t('app', 'Status Message'),
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

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }
}
