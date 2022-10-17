<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tbl_milk_collection_summary".
 *
 * @property integer $milk_collection_summary_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $total_qty
 * @property string $avg_rate
 * @property string $total_amount
 * @property integer $sample_count
 * @property integer $auto_count
 * @property integer $manual_count
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMilkCollectionSummary extends \app\models\ChildModel {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['date_time_of_collection', 'created_at', 'updated_at'], 'safe', 'on' => ['androidsync']],
            [['milk_collection_summary_code', 'date_time_of_collection', 'created_at', 'updated_at', 'data_post_status', 'pick_datetime'], 'safe'],
            [['shift_code', 'sample_count', 'auto_count', 'manual_count'], 'safe'],
            [['avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'total_qty', 'avg_rate', 'total_amount', 'received_timestamp'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['data_post_status'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_collection_summary_code' => Yii::t('app', 'Milk Collection Summary Code'),
            'date_time_of_collection' => Yii::t('app', 'Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'avg_fat' => Yii::t('app', 'Avg FAT'),
            'avg_snf' => Yii::t('app', 'Avg SNF'),
            'kg_fat' => Yii::t('app', 'Kg FAT'),
            'kg_snf' => Yii::t('app', 'Kg SNF'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'sample_count' => Yii::t('app', 'Sample Count'),
            'auto_count' => Yii::t('app', 'Auto Count'),
            'manual_count' => Yii::t('app', 'Manual Count'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
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

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getPickRecords($limit = 10, $considerPickData = true) {
        $datetime = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $query = $this->find()
                ->where(['or', ['data_post_status' => 0], ['data_post_status' => NULL], ['data_post_status' => '']])
                ->limit($limit)
                ->orderBy(['milk_collection_summary_code' => SORT_ASC]);

        if ($considerPickData) {
            $pendingDataQuery = $this->find()
                    ->where(['data_post_status' => 1])
                    ->andWhere(['<', 'pick_datetime', $datetime]);
            $pendingDataQuery->orderBy(['milk_collection_summary_code' => SORT_ASC])->limit(5);

            return $unionQuery = (new ActiveQuery(TblMilkCollectionSummary::className()))->from([
                        'pending_data' => $query->union($pendingDataQuery, TRUE)
                    ])->all();
        } else {
            return $query->all();
        }
    }

    public function updateFileStatus($value) {
        return $this->updateAll(['data_post_status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['milk_collection_summary_code' => $value]);
    }

    public function updateFileUploadStatus($value) {
        return $this->updateAll(['data_post_status' => 2, 'pick_datetime' => date('Y-m-d H:i:s')], ['milk_collection_summary_code' => $value]);
    }

}
