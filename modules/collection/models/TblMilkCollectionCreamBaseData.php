<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblMember;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\geo\models\TblVillages;
use app\modules\dcsoperation\models\TblShift;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\general\models\TblSocietyVendor;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\collection\models\TblCollectionDataAlias;
use app\modules\configuration\models\TblUnionRatechartRange;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\collection\models\TblProvisionalMilkCollection;
use app\modules\collection\models\TblProvisionalMilkCollectionHistory;

/**
 * This is the model class for table "tbl_milk_collection_cream_base_data".
 *
 * @property integer $milk_collection_code
 * @property string $member_code
 * @property string $dcs_code
 * @property string $name
 * @property string $mobile_no
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property string $auto_flag
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $village_code
 * @property integer $sample_no
 * @property string $type_of_data_receive
 * @property string $purchase_rate_code
 * @property string $error_log
 * @property integer $ack
 * @property string $soc_bmc_flag
 * @property string $dt_date
 * @property string $sms_status
 * @property string $sms_msgid
 * @property string $sms_mobile
 * @property string $sms_errorlog
 * @property string $sms_timestamp
 * @property integer $data_post_status
 * @property string $clr
 * @property string $status
 * @property integer $qty_mode
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $no_of_can
 * @property integer $milk_quality_type_code
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $route_code
 * @property string $bmc_code
 * @property string $converted_qty
 * @property integer $is_approved
 * @property string $data_post_id
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $picked_datetime
 * @property string $ftp_txn_file_name
 * @property string $tag_1
 * @property string $tag_2
 * @property integer $ftp_txn_log_id
 * @property string $error_desc
 * @property integer $originating_type
 * @property string $last_edited_type
 * @property string $remarks
 * @property string $sync_status
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $version_no
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $converted_qty_mode
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property integer $dcs_payment_cycle_code
 * @property integer $milk_analyser_type_code
 * @property integer $ws_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $incentive
 * @property string $deduction
 * @property string $total_amount
 * @property string $own_bmc_code
 * @property string $own_mcc_plant_code
 * @property integer $send_status
 * @property string $response_datetime
 * @property string $adt_param
 * @property string $adt_value
 * @property integer $txfarmer_id
 * @property string $data_inserted_from
 * @property integer $is_provisional
 * @property string $device_lat
 * @property string $device_long
 * @property string $mob_lat
 * @property string $mob_long
 * @property string $dpu_rtpl
 * @property string $dpu_amount
 * @property string $dpu_incentive
 * @property string $dpu_deduction
 * @property string $dpu_total_amount
 * @property integer $is_rate_recalc
 * @property string $purchase_rate_code_old
 * @property string $scheme_rate
 * @property string $scheme_rate_code
 * @property string $actual_rate
 * @property string $salt
 * @property string $freezing_point
 * @property string $temperature
 * @property string $batch_no
 * @property string $can_no
 */
class TblMilkCollectionCreamBaseData extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_cream_base_data';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['milk_type_code', 'sample_no', 'ack', 'data_post_status', 'qty_mode', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'is_approved', 'ftp_txn_log_id', 'originating_type', 'converted_qty_mode', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'send_status', 'txfarmer_id', 'is_provisional', 'is_rate_recalc'], 'integer'],
                [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'adt_value', 'dpu_rtpl', 'dpu_amount', 'dpu_incentive', 'dpu_deduction', 'dpu_total_amount', 'scheme_rate', 'actual_rate', 'salt', 'freezing_point', 'temperature'], 'number'],
                [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'qlty_time', 'qty_time', 'created_at', 'updated_at', 'picked_datetime', 'response_datetime'], 'safe'],
                [['remarks', 'device_lat', 'device_long', 'mob_lat', 'mob_long'], 'string'],
                [['member_code', 'version_no', 'scheme_rate_code', 'can_no'], 'string', 'max' => 20],
                [['dcs_code', 'bmc_code', 'plant_code', 'mcc_plant_code'], 'string', 'max' => 12],
                [['name'], 'string', 'max' => 100],
                [['mobile_no', 'sms_mobile', 'resp_status', 'resp_desc', 'ftp_txn_file_name', 'error_desc', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'adt_param', 'batch_no'], 'string', 'max' => 255],
                [['auto_flag', 'soc_bmc_flag'], 'string', 'max' => 5],
                [['shift_code'], 'string', 'max' => 30],
                [['village_code'], 'string', 'max' => 6],
                [['type_of_data_receive', 'error_log', 'sms_msgid', 'data_inserted_from'], 'string', 'max' => 50],
                [['purchase_rate_code', 'purchase_rate_code_old'], 'string', 'max' => 11],
                [['sms_status', 'route_code', 'tag_1', 'tag_2', 'own_bmc_code', 'own_mcc_plant_code'], 'string', 'max' => 10],
                [['sms_errorlog'], 'string', 'max' => 200],
                [['status'], 'string', 'max' => 15],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['data_post_id'], 'string', 'max' => 55],
                [['last_edited_type', 'sync_status'], 'string', 'max' => 1],
                [['union_code'], 'string', 'max' => 3],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_collection_code' => Yii::t('app', 'Milk Collection Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Code'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'error_log' => Yii::t('app', 'Error Log'),
            'ack' => Yii::t('app', 'Ack'),
            'soc_bmc_flag' => Yii::t('app', 'Soc Bmc Flag'),
            'dt_date' => Yii::t('app', 'Dt Date'),
            'sms_status' => Yii::t('app', 'Sms Status'),
            'sms_msgid' => Yii::t('app', 'Sms Msgid'),
            'sms_mobile' => Yii::t('app', 'Sms Mobile'),
            'sms_errorlog' => Yii::t('app', 'Sms Errorlog'),
            'sms_timestamp' => Yii::t('app', 'Sms Timestamp'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'clr' => Yii::t('app', 'Clr'),
            'status' => Yii::t('app', 'Status'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'route_code' => Yii::t('app', 'Route Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'data_post_id' => Yii::t('app', 'Data Post ID'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'ftp_txn_file_name' => Yii::t('app', 'Ftp Txn File Name'),
            'tag_1' => Yii::t('app', 'Tag 1'),
            'tag_2' => Yii::t('app', 'Tag 2'),
            'ftp_txn_log_id' => Yii::t('app', 'Ftp Txn Log ID'),
            'error_desc' => Yii::t('app', 'Error Desc'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'last_edited_type' => Yii::t('app', 'Last Edited Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'version_no' => Yii::t('app', 'Version No'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'incentive' => Yii::t('app', 'Incentive'),
            'deduction' => Yii::t('app', 'Deduction'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'send_status' => Yii::t('app', 'Send Status'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'adt_param' => Yii::t('app', 'Adt Param'),
            'adt_value' => Yii::t('app', 'Adt Value'),
            'txfarmer_id' => Yii::t('app', 'Txfarmer ID'),
            'data_inserted_from' => Yii::t('app', 'Data Inserted From'),
            'is_provisional' => Yii::t('app', 'Is Provisional'),
            'device_lat' => Yii::t('app', 'Device Lat'),
            'device_long' => Yii::t('app', 'Device Long'),
            'mob_lat' => Yii::t('app', 'Mob Lat'),
            'mob_long' => Yii::t('app', 'Mob Long'),
            'dpu_rtpl' => Yii::t('app', 'Dpu Rtpl'),
            'dpu_amount' => Yii::t('app', 'Dpu Amount'),
            'dpu_incentive' => Yii::t('app', 'Dpu Incentive'),
            'dpu_deduction' => Yii::t('app', 'Dpu Deduction'),
            'dpu_total_amount' => Yii::t('app', 'Dpu Total Amount'),
            'is_rate_recalc' => Yii::t('app', 'Is Rate Recalc'),
            'purchase_rate_code_old' => Yii::t('app', 'Purchase Rate Code Old'),
            'scheme_rate' => Yii::t('app', 'Scheme Rate'),
            'scheme_rate_code' => Yii::t('app', 'Scheme Rate Code'),
            'actual_rate' => Yii::t('app', 'Actual Rate'),
            'salt' => Yii::t('app', 'Salt'),
            'freezing_point' => Yii::t('app', 'Freezing Point'),
            'temperature' => Yii::t('app', 'Temperature'),
            'batch_no' => Yii::t('app', 'Batch No'),
            'can_no' => Yii::t('app', 'Can No'),
        ];
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateCode() {
        return $this->hasOne(TblPurchaseRate::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkCollectionCode() {
        return $this->hasOne(TblMilkCollection::className(), ['milk_collection_code' => 'milk_collection_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkCollection() {
        return $this->hasOne(TblMilkCollection::className(), ['milk_collection_code' => 'milk_collection_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    /**
     * @inheritdoc
     * @return TblMilkCollectionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMilkCollectionQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkQualityCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getMilkCollData() {
        return $this->find()
                        ->joinWith(['societyVendorCode'])
                        ->where(['or', ['data_post_status' => [0, 3]], ['data_post_status' => NULL]])
                        ->andWhere(['tbl_society_vendor.vendor_code' => 'STELLAPPS'])
                        ->limit(200)
                        ->orderby('date_time_of_collection ASC')
                        ->all();
    }

    public function updateMilkColl($value) {
        return $this->updateAll(['data_post_status' => 1], ['milk_collection_code' => $value]);
    }

    public function getSocietyVendorCode() {
        return $this->hasOne(TblSocietyVendor::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getCollection($data) {
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        return $this->find()->select(['member_code', 'fat', 'snf', 'qty', 'amount', 'date_time_of_collection', 'sample_no', 'shift_code', 'clr', 'rtpl', 'qlty_auto', 'qty_auto', 'milk_type_code'])
                        ->where(['member_code' => $this->member_code])->andWhere("date_time_of_collection between '$from_date' and '$to_date' ")->orderBy(['date_time_of_collection' => SORT_ASC, 'shift_code' => SORT_ASC, 'sample_no' => SORT_DESC])->all();
    }

    public function getCollectionDatewise() {
        return $this->find()->select(['member_code', 'fat', 'snf', 'qty', 'amount', 'date_time_of_collection', 'sample_no', 'shift_code', 'clr', 'rtpl', 'qlty_auto', 'qty_auto', 'milk_type_code'])
                        ->where(['member_code' => $this->member_code, 'cast(date_time_of_collection as date)' => $this->collection_date])->orderBy(['date_time_of_collection' => SORT_ASC, 'shift_code' => SORT_ASC, 'sample_no' => SORT_DESC])->all();
    }

    public function memberCollectionData($from_date, $to_date, $society_code) {
        return $this->find()->select(['member_code', 'name', 'milk_type_code', 'fat', 'snf', 'qty', 'rtpl', 'amount', 'shift_code', 'date_time_of_collection', 'sample_no'])
                        ->where(['dcs_code' => $society_code])
                        ->andFilterWhere(['>=', 'date_time_of_collection', $from_date])
                        ->andFilterWhere(['<=', 'date_time_of_collection', $to_date])
                        ->all();
    }

    public function getSampleNo() {
        $data = $this->find()
                ->select('max(sample_no) as sample_no')
                ->where(['dcs_code' => $this->dcs_code, 'shift_code' => $this->shift_code, 'CONVERT(date,date_time_of_collection)' => Yii::$app->formatter->asDate($this->date_time_of_collection, DATE_FORMAT)])
                ->one();
        $sample_no = (int) $data['sample_no'] + 1;
        return $sample_no;
    }

    public function getExistingData() {
        return $this->find()
                        ->where(['dcs_code' => $this->dcs_code, 'sample_no' => $this->sample_no, 'milk_type_code' => $this->milk_type_code, 'date_time_of_collection' => $this->date_time_of_collection, 'shift_code' => $this->shift_code])
                        ->one();
    }

    public function setUuid($attribute, $params) {
        $this->data_post_id = !empty($this->data_post_id) ? $this->data_post_id : $this->x_col1;
        $this->dpu_rtpl = $this->rtpl;
        $this->dpu_amount = $this->amount;
        $this->dpu_incentive = $this->incentive;
        $this->dpu_deduction = $this->deduction;
        $this->dpu_total_amount = $this->total_amount;
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getRateRange() {
        return $this->hasOne(TblUnionRatechartRange::className(), ['union_code' => 'union_code', 'animal_type_code' => 'milk_type_code']);
    }

}
