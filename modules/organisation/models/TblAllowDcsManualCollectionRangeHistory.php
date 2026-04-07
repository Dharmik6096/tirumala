<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_allow_dcs_manual_collection_range_history".
 *
 * @property integer $id
 * @property string $manual_collection_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $from_shift
 * @property string $to_date
 * @property string $to_shift
 * @property integer $is_weight_manual
 * @property integer $is_quality_manual
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblAllowDcsManualCollectionRangeHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_allow_dcs_manual_collection_range_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['manual_collection_code'], 'required'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'safe'],
                [['from_date', 'to_date', 'from_shift', 'to_shift', 'created_at', 'updated_at'], 'safe'],
                [['is_weight_manual', 'is_quality_manual', 'originating_type'], 'integer'],
                [['manual_collection_code'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'history_created_at', 'history_created_by', 'operation_type', 'status', 'remark'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'request_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'manual_collection_code' => Yii::t('app', 'Manual Collection Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'is_weight_manual' => Yii::t('app', 'Is Weight Manual'),
            'is_quality_manual' => Yii::t('app', 'Is Quality Manual'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'request_type' => Yii::t('app', 'Request Type'),
        ];
    }

}
