<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_transaction_history".
 *
 * @property integer $id
 * @property string $asset_transaction_code
 * @property integer $asset_detail_code
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $asset_code
 * @property string $serial_number
 * @property string $union_code
 * @property string $received_date
 * @property string $received_by
 * @property integer $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblAssetTransactionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_transaction_code', 'from_type', 'from_dest', 'to_type', 'to_dest', 'asset_code', 'serial_number', 'union_code', 'received_by', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
            [['asset_detail_code', 'qty', 'put_to_use_date', 'remain_qty', 'reamrks'], 'safe'],
            [['asset_detail_code', 'status', 'remarks'], 'safe'],
            [['received_date', 'created_at', 'updated_at', 'history_created_at', 'transaction_date', 'asset_type', 'sap_code', 'current_status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'asset_transaction_code' => Yii::t('app', 'Asset Transaction Code'),
            'asset_detail_code' => Yii::t('app', 'Asset Detail Code'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'union_code' => Yii::t('app', 'Union Code'),
            'received_date' => Yii::t('app', 'Received Date'),
            'received_by' => Yii::t('app', 'Received By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'current_status' => Yii::t('app', 'Current Status'),
        ];
    }

}
