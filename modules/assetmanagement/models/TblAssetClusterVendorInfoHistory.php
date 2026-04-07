<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_cluster_vendor_info_history".
 *
 * @property integer $id
 * @property string $asset_cluster_vendor_info_code
 * @property string $asset_code
 * @property string $serial_number
 * @property string $cluster_email
 * @property string $cluster_mobile
 * @property string $vendor_email
 * @property string $vendor_mobile
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblAssetClusterVendorInfoHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_cluster_vendor_info_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_cluster_vendor_info_code', 'asset_code', 'serial_number', 'cluster_email', 'cluster_mobile', 'vendor_email', 'vendor_mobile', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'history_created_at', 'history_created_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_cluster_vendor_info_code' => Yii::t('app', 'Asset Cluster Vendor Info Code'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'cluster_email' => Yii::t('app', 'Cluster Email'),
            'cluster_mobile' => Yii::t('app', 'Cluster Mobile'),
            'vendor_email' => Yii::t('app', 'Vendor Email'),
            'vendor_mobile' => Yii::t('app', 'Vendor Mobile'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
