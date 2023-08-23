<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_dispatch_consolidated".
 *
 * @property string $bmc_dispatch_consolidated_code
 * @property string $trip_code
 * @property string $total_qty
 * @property string $kg_fat
 * @property string $kg_snf
 * @property integer $rejection_count
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblBmcDispatchConsolidated extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_dispatch_consolidated';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bmc_dispatch_consolidated_code', 'trip_code', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['total_qty', 'kg_fat', 'kg_snf'], 'safe'],
                [['rejection_count', 'originating_type'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_dispatch_consolidated_code' => Yii::t('app', 'Bmc Dispatch Consolidated Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kf Snf'),
            'rejection_count' => Yii::t('app', 'Rejection Count'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
