<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_detail_bom_history".
 *
 * @property integer $id
 * @property integer $asset_detail_bom_code
 * @property string $asset_detail_code
 * @property integer $spare_code
 * @property string $serial_number
 * @property integer $qty
 * @property string $union_code
 * @property integer $is_active
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblAssetDetailBomHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_detail_bom_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_detail_bom_code', 'spare_code', 'qty', 'is_active', 'originating_type'], 'safe'],
            [['asset_detail_code', 'created_at', 'updated_at', 'history_created_at', 'asset_detail_bom_code', 'spare_code', 'qty', 'is_active', 'originating_type'], 'safe'],
            [['serial_number', 'union_code', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_detail_bom_code' => Yii::t('app', 'Asset Detail Bom Code'),
            'asset_detail_code' => Yii::t('app', 'Asset Detail Code'),
            'spare_code' => Yii::t('app', 'Spare Code'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'qty' => Yii::t('app', 'Qty'),
            'union_code' => Yii::t('app', 'Union Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
