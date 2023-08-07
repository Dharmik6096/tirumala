<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_bom_history".
 *
 * @property integer $id
 * @property integer $asset_bom_code
 * @property string $asset_code
 * @property string spare_code
 * @property string $union_code
 * @property integer $is_serial_number
 * @property integer $qty
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
class TblAssetBomHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_bom_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'union_code', 'spare_code', 'asset_code', 'is_serial_number', 'qty', 'is_active', 'originating_type'], 'safe'],
            [['history_created_at', 'history_created_at'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_bom_code' => Yii::t('app', 'Asset Bom Code'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'spare_code' => Yii::t('app', 'Spare Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'is_serial_number' => Yii::t('app', 'Is Serial Number'),
            'qty' => Yii::t('app', 'Qty'),
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
