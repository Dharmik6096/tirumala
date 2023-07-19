<?php

namespace app\modules\assetmanagement\models;

use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_asset_detail_bom".
 *
 * @property integer $asset_detail_bom_code
 * @property integer $asset_detail_code
 * @property string $spare_name
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
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblAssetDetailBom extends \app\models\ChildModel {

    public $asset_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_detail_bom';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['spare_code'], 'required'],
                [['asset_detail_code', 'qty', 'is_active', 'originating_type'], 'integer'],
                [['spare_code', 'serial_number', 'created_by', 'updated_by'], 'string'],
                [['asset_code', 'spare_code', 'serial_number'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'created_at', 'updated_at',], 'safe'],
                [['asset_detail_code', 'spare_code'], 'required', 'on' => 'importCsv'],
                [['spare_code'], 'checkUnique', 'on' => 'importCsv'],
                [['spare_code'], 'checkUnique', 'on' => 'create', 'except' => ['update']],
                [['qty'], 'default', 'value' => 1],
                [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'asset_detail_bom_code' => Yii::t('app', 'Asset Detail Bom Code'),
            'asset_detail_code' => Yii::t('app', 'Asset Detail Code'),
            'spare_code' => Yii::t('app', 'Spare Name'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'qty' => Yii::t('app', 'Qty'),
            'union_code' => Yii::t('app', 'Union Code'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getAssetCode() {
        return $this->hasOne(TblAssetMaster::className(), ['asset_code' => 'asset_code']);
    }

    public function getSpareCode() {
        return $this->hasOne(TblAssetMaster::className(), ['asset_code' => 'spare_code']);
    }

    public function getAssetDetailCode() {
        return $this->hasOne(TblAssetDetail::className(), ['asset_detail_code' => 'asset_detail_code']);
    }

    public function checkUnique($attribute) {
        $data = 0;
        $data = $this->find()->where(['spare_code' => $this->spare_code, 'serial_number' => $this->serial_number, 'is_active' => $this->is_active])
                ->count();

        if ($data != 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Spare name/Serial Number has already been taken'));
        }
    }

//    public function getOldSrNo($asset_code, $slocType, $ref_code, $spare_code) {
//        return $this->find()
//                        ->select(['DISTINCT(tbl_asset_detail_bom.spare_code) as spare_code,tbl_asset_detail_bom.serial_number'])
//                        ->innerJoin('tbl_asset_detail', 'tbl_asset_detail.asset_detail_code = tbl_asset_detail_bom.asset_detail_code')
//                        ->innerJoin('tbl_asset_transaction', 'tbl_asset_transaction.asset_detail_code = tbl_asset_detail_bom.asset_detail_code')
//                        ->innerJoin('tbl_store_location', 'tbl_store_location.store_location_code = tbl_asset_transaction.to_dest')
//                        ->where(['tbl_asset_transaction.asset_code' => $asset_code, 'tbl_asset_transaction.status' => '2', 'tbl_store_location.store_location_type' => $slocType, 'tbl_store_location.reference_code' => $ref_code, 'tbl_asset_detail_bom.spare_code' => $spare_code])->asArray()->all();
//    }
    public function getOldSrNo($asset_code, $slocType, $ref_code, $spare_code) {
        return $this->find()
                        ->select(['DISTINCT(tbl_asset_detail_bom.spare_code) as spare_code,tbl_asset_detail_bom.serial_number'])
                        ->innerJoin('tbl_asset_bom', 'tbl_asset_bom.spare_code = tbl_asset_detail_bom.spare_code')
                        ->innerJoin('tbl_asset_transaction', 'tbl_asset_transaction.asset_detail_code = tbl_asset_detail_bom.asset_detail_code')
                        ->innerJoin('tbl_store_location', 'tbl_store_location.store_location_code = tbl_asset_transaction.to_dest')
                        ->where(['tbl_asset_transaction.asset_code' => $asset_code, 'tbl_asset_transaction.status' => '2', 'tbl_store_location.store_location_type' => $slocType, 'tbl_store_location.reference_code' => $ref_code, 'tbl_asset_detail_bom.spare_code' => $spare_code, 'tbl_asset_bom.is_serial_number' => 1])->asArray()->all();
    }

    public function getNewSrNo($asset_code) {
        return $this->find()
                        ->select(['DISTINCT(tbl_asset_detail_bom.spare_code) as spare_code,tbl_asset_detail_bom.serial_number'])
                        ->innerJoin('tbl_asset_detail', 'tbl_asset_detail.asset_detail_code = tbl_asset_detail_bom.asset_detail_code')
                        ->innerJoin('tbl_asset_transaction', 'tbl_asset_transaction.asset_detail_code = tbl_asset_detail_bom.asset_detail_code')
                        ->innerJoin('tbl_store_location', 'tbl_store_location.store_location_code = tbl_asset_transaction.to_dest')
                        ->where(['tbl_asset_transaction.asset_code' => $asset_code, 'tbl_asset_transaction.status' => '0'])->asArray()->all();
    }

}
