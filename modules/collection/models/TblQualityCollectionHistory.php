<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_quality_collection_history".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $sample_no
 * @property string $date_time_of_collection
 * @property string $shift_code
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $quality_datetime
 * @property integer $retest_count
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $device_id
 * @property string $version_no
 * @property integer $doc_no
 * @property integer $auto_flag
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property integer $qlty_auto
 * @property string $milk_analyser_type_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $own_mcc_plant_code
 * @property string $own_bmc_code
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property string $adt_param
 * @property string $adt_value
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblQualityCollectionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_quality_collection_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid'], 'required'],
            [['sample_no', 'retest_count', 'doc_no', 'auto_flag', 'originating_type', 'qlty_auto'], 'safe'],
            [['date_time_of_collection', 'quality_datetime', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'adt_value'], 'safe'],
            [['uuid'], 'safe'],
            [['shift_code'], 'safe'],
            [['union_code'], 'safe'],
            [['plant_code', 'mcc_plant_code'], 'safe'],
            [['bmc_code', 'own_mcc_plant_code', 'own_bmc_code'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['device_id'], 'safe'],
            [['version_no', 'milk_analyser_type_code'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'adt_param'], 'safe'],
            [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'uuid' => Yii::t('app', 'Uuid'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'quality_datetime' => Yii::t('app', 'Quality Datetime'),
            'retest_count' => Yii::t('app', 'Retest Count'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'device_id' => Yii::t('app', 'Device ID'),
            'version_no' => Yii::t('app', 'Version No'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'adt_param' => Yii::t('app', 'Adt Param'),
            'adt_value' => Yii::t('app', 'Adt Value'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
