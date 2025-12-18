<?php

namespace app\modules\assetmanagement\models;

use Yii;
use app\modules\assetmanagement\models\TblAssetGroup;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;
use app\modules\assetmanagement\models\TblAssetTransaction;
use app\modules\complaint\models\TblComplainProduct;

/**
 * This is the model class for table "tbl_asset_master".
 *
 * @property string $asset_code
 * @property string $asset_group_code
 * @property string $asset_name
 * @property integer $is_serial_number
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 */
class TblAssetMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['asset_group_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'asset_group_code');
                }, 'on' => 'importCsv'],
                [['cmpl_product_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'cmpl_product_code');
                }, 'on' => 'importCsv'],
                [['asset_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'asset_type');
                }, 'on' => 'importCsv'],
                [['asset_group_code', 'asset_name'], 'required'],
                [['is_serial_number', 'is_spare'], 'required', 'on' => 'importCsv'],
                [['asset_group_code', 'asset_name', 'created_by', 'updated_by', 'local_name'], 'string'],
                [['is_serial_number', 'is_spare'], 'integer'],
                [['is_serial_number', 'is_spare'], 'boolean', 'on' => 'importCsv'],
                [['is_active'], 'default', 'value' => 1],
                [['created_at', 'updated_at', 'cmpl_product_code', 'ref_code', 'is_spare', 'asset_type_code'], 'safe'],
                [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['asset_group_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAssetGroup::className(), 'targetAttribute' => ['asset_group_code' => 'asset_group_code']],
                [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
                [['cmpl_product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblComplainProduct::className(), 'targetAttribute' => ['cmpl_product_code' => 'cmpl_product_code']],
                [['asset_name'], 'unique'],
                [['asset_group_code'], 'assignAutoData', 'skipOnError' => true],
                [['ref_code'], 'number'],
                ['ref_code', 'unique', 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Reference Code has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'asset_code' => Yii::t('app', 'Asset Code'),
            'asset_group_code' => Yii::t('app', 'Asset Group'),
            'asset_name' => Yii::t('app', 'Asset Name'),
            'is_serial_number' => Yii::t('app', 'Is Serial Number'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
            'union_code' => Yii::t('app', 'Union'),
            'cmpl_product_code' => Yii::t('app', 'Asset Type'),
            'ref_code' => Yii::t('app', 'Reference Code'),
            'is_spare' => Yii::t('app', 'Is Spare'),
            'asset_type_code' => Yii::t('app', 'Asset Type'),
        ];
    }

    public function getAssetGroupCode() {
        return $this->hasOne(TblAssetGroup::className(), ['asset_group_code' => 'asset_group_code']);
    }

    public function geAssetList() {
        $value = $this->getAsset();
        $value = ArrayHelper::map($value, 'asset_code', 'asset_name');
        return $value;
    }

    public function getAsset() {
        $query = $this->find()->select(['asset_code', 'asset_name'])->where(['is_active' => 1]);
        return $query->all();
    }

    public static function getSerialNo($dcs_code = NULL, $asset_code = NULL, $slocType = 3) {

        if (!empty($dcs_code) && !empty($asset_code)) {
            $asset = Self::findOne($asset_code);
            if (!empty($asset) && $asset->is_serial_number == 1) {
//                $details = TblAssetDetail::find()->select('serial_number')->joinWith('storeLocCode')
//                        ->where(['asset_code' => $asset_code, 'tbl_store_location.store_location_type' => 3, 'tbl_store_location.reference_code' => $dcs_code])
//                        ->orderBy(['put_to_use_date' => SORT_DESC])
//                        ->one();
                $details = TblAssetTransaction::find()->select(['tbl_asset_transaction.serial_number'])->joinWith(['toStoreLocCode', 'assetDetail'])
                        ->where(['tbl_asset_transaction.status' => [2], 'tbl_asset_transaction.asset_code' => $asset_code, 'tbl_store_location.store_location_type' => $slocType, 'tbl_store_location.reference_code' => $dcs_code])
                        ->orderBy(['tbl_asset_detail.put_to_use_date' => SORT_DESC])
                        ->one();

                if (!empty($details)) {
                    $sno = $details->serial_number;
                    return $sno;
                }
            } else {
                return 'noserial';
            }
        }
        return '';
    }

    public function getAssetData() {
        return $this->find()
                        ->where(['asset_code' => $this->asset_code])
                        ->one();
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getAssetType() {
        return $this->hasOne(TblComplainProduct::className(), ['cmpl_product_code' => 'cmpl_product_code']);
    }

    public function assignAutoData($attribute, $params) {
        $this->union_code = $this->assetGroupCode->union_code;
    }

//    public static function getSrNo($to_code = '', $asset_code = NULL) {
//
//        if (!empty($to_code)) {
//            $asset = TblAssetTransaction::find()
//                            ->innerJoin('tbl_store_location', 'tbl_store_location.store_location_code = tbl_asset_transaction.to_dest')
//                            ->where(['tbl_store_location.reference_code' => $to_code, 'tbl_asset_transaction.serial_number' => $asset_code])->one();
//            if (!empty($asset)) {
//                $sno = $asset->serial_number;
//                return $sno;
//            }
//        }
//        return '';
//    }

    public static function getSrNo($to_code = '', $asset = NULL, $srno = NULL) {

        if (!empty($to_code)) {
            $assets = TblAssetTransaction::find()
                            ->innerJoin('tbl_store_location', 'tbl_store_location.store_location_code = tbl_asset_transaction.to_dest')
                            ->where(['tbl_store_location.reference_code' => $to_code, 'tbl_asset_transaction.asset_code' => $asset, 'tbl_asset_transaction.serial_number' => $srno])->one();

            if (!empty($assets)) {
                $sno = $assets['serial_number'];
                return $sno;
            }
        }
        return '';
//            return $assets;
    }

//        public static function getSrNo($asset_code = NULL) {
//
//        if (!empty($asset_code)) {
//            $asset = Self::findOne($asset_code);
//            if (!empty($asset) && $asset->is_serial_number == 1) {
//                $details = TblAssetTransaction::find()->select(['tbl_asset_transaction.serial_number'])
//                        ->where(['tbl_asset_transaction.status' => [2], 'tbl_asset_transaction.serial_number' => $asset_code])
//                        ->one();
//
//                if (!empty($details)) {
//                    $sno = $details->serial_number;
//                    return $sno;
//                }
//            }
//        }
//        return '';
//    }
    public function getAssetBom() {
        return $this->hasMany(TblAssetBom::className(), ['asset_code' => 'asset_code']);
    }

    public function getAssetTypeCode() {
        return $this->hasOne(TblAssetType::className(), ['asset_type_code' => 'asset_type_code']);
    }

}
