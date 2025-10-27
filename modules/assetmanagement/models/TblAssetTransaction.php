<?php

namespace app\modules\assetmanagement\models;

use Yii;
//use app\modules\organisation\models\TblManufacturer;
use app\modules\organisation\models\TblCustomerMaster;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblUnions;
use app\modules\assetmanagement\models\TblAssetSet;
use app\modules\details\models\TblContactDetails;
use app\modules\organisation\models\TblMccPlant;
use app\modules\document\models\TblAttachment;
use app\modules\assetmanagement\models\TblAssetClusterVendorInfo;

/**
 * This is the model class for table "tbl_asset_transaction".
 *
 * @property integer $asset_transaction_code
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
 */
class TblAssetTransaction extends \app\models\ChildModel {

    public $is_outward, $in_use, $from_sloc, $to_sloc, $selected_sr_no, $is_serial_number, $in_ward, $warranty_period, $maintanance_duration_in_days, $asset_group_code, $purchase_date;
    public $from_plant, $from_mcc, $from_bmc, $from_dcs, $to_plant, $to_mcc, $to_bmc, $to_dcs, $make;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['asset_detail_code'], 'required'],
            [['asset_detail_code', 'status'], 'integer'],
            [['from_type', 'from_dest', 'to_type', 'to_dest', 'asset_code', 'serial_number', 'union_code', 'received_by', 'created_by', 'updated_by', 'qty', 'in_ward', 'selected_sr_no', 'remarks', 'put_to_use_date', 'from_plant', 'from_mcc', 'from_bmc', 'from_dcs', 'to_plant', 'to_mcc', 'to_bmc', 'to_dcs', 'sap_code', 'remain_qty', 'detail_code', 'manufacturer_serial_number'], 'safe'],
            [['received_date', 'created_at', 'updated_at', 'asset_type', 'transaction_date'], 'safe'],
            [['asset_type'], 'default', 'value' => 0],
            [['to_type', 'to_dest', 'transaction_date'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->is_outward == '1';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#is_outward').val() == '1'; 
          }"],
            [['transaction_date'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->is_outward == '0';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#is_outward').val() == '0'; 
          }"],
            [['asset_code', 'from_type', 'to_type', 'to_dest', 'transaction_date'], 'required', 'on' => 'importCsv'],
            [['serial_number'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->assetCode, 'is_serial_number') == '1';
                }, 'except' => ['validateOut']],
            [['qty'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->assetCode, 'is_serial_number') == '0';
                }, 'except' => ['validateOut']],
            [['asset_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'asset_code');
                }, 'skipOnError' => true, 'on' => 'importCsv'],
            [['asset_code'], 'assignAutoData', 'skipOnError' => true, 'on' => 'importCsv'],
            [['asset_code'], 'checkUnique', 'skipOnError' => true],
            [['asset_code'], 'getDetailCode', 'skipOnError' => true, 'except' => ['importCsv', 'assetTransfer', 'create']],
            //   ['serial_number', 'unique', 'targetAttribute' => ['serial_number', 'asset_code', 'from_type', 'from_dest', 'to_type', 'to_dest', 'transaction_date'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Serial No. has already been taken.')],
            [['from_dest'], 'exist', 'skipOnError' => true,
                'targetClass' => ($this->from_type == 'VEN') ? TblCustomerMaster::className() : TblStoreLocation::className()
                , 'targetAttribute' =>
                ($this->from_type == 'VEN') ? ['from_dest' => 'customer_code'] : ['from_dest' => 'store_location_code']
            ],
            [['to_dest'], 'exist', 'skipOnError' => true, 'targetClass' => TblStoreLocation::className(), 'targetAttribute' => ['to_dest' => 'store_location_code']],
            [['received_date', 'received_by'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->is_outward == '2';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#is_outward').val() == '2'; 
          }"],
//            [['sap_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
//                    return $model->status == 2;
//                }, 'on' => 'importCsv'],
//            [['sap_code'], 'validateDestination', 'skipOnError' => true, 'on' => ['setmovement']],
            [['from_type'], 'validateOutValidate', 'skipOnError' => true, 'except' => ['setmovement']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'asset_transaction_code' => Yii::t('app', 'Asset Transaction Code'),
            'asset_detail_code' => Yii::t('app', 'Asset Detail Code'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'asset_code' => Yii::t('app', 'Asset'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'union_code' => Yii::t('app', 'Union'),
            'received_date' => Yii::t('app', 'Received Date'),
            'received_by' => Yii::t('app', 'Received By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'in_ward' => Yii::t('app', 'In Use'),
            'remain_qty' => Yii::t('app', 'Qty'),
            'asset_group_code' => Yii::t('app', 'Asset Group'),
            'from_mcc' => Yii::t('app', 'From MCC'),
            'from_plant' => Yii::t('app', 'From Plant'),
            'from_dcs' => Yii::t('app', 'From DCS'),
            'to_mcc' => Yii::t('app', 'To MCC'),
            'to_plant' => Yii::t('app', 'To Plant'),
            'to_dcs' => Yii::t('app', 'To DCS'),
            'sap_code' => Yii::t('app', 'SAP Code'),
            'detail_code' => Yii::t('app', 'Detail Code'),
            'manufacturer_serial_number' => Yii::t('app', 'Manufacturer Serial No.'),
        ];
    }

    public function getAssetCode() {
        return $this->hasOne(TblAssetMaster::className(), ['asset_code' => 'asset_code']);
    }

    public function getAssetDetail() {
        return $this->hasOne(TblAssetDetail::className(), ['asset_detail_code' => 'asset_detail_code']);
    }

    public function getFromStoreLocCode() {
        return $this->hasOne(TblStoreLocation::className(), ['store_location_code' => 'from_dest']);
    }

    public function getToStoreLocCode() {
        return $this->hasOne(TblStoreLocation::className(), ['store_location_code' => 'to_dest']);
    }

    public function getManufacturerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'from_dest']);
    }

    public function getFromSlocCode() {
        return $this->hasOne(TblStoreLocation::className(), ['sloc_code' => 'from_sloc']);
    }

    public function getToSlocCode() {
        return $this->hasOne(TblStoreLocation::className(), ['sloc_code' => 'to_sloc']);
    }

    public function getContactDetailCode() {
        return $this->hasOne(TblContactDetails::className(), ['detail_code' => 'detail_code']);
    }

    public static function getSerialNo($serial_number) {
        if (!empty($serial_number)) {
            $details = TblAssetTransaction::find()->select(['to_dest' => 'tbl_store_location.store_location_name'])
                    ->joinWith(['toStoreLocCode'])
                    ->where(['tbl_asset_transaction.status' => [2], 'tbl_asset_transaction.serial_number' => $serial_number, 'tbl_store_location.store_location_type' => 3])
                    ->one();
            if (!empty($details)) {
                return 'Asset is already installed at <b>' . $details->to_dest . '</b>. Are you sure to transfer?';
            }
        }
        return '';
    }

    public function assignAutoData($attribute, $params) {
        $this->union_code = isset($this->assetCode) ? $this->assetCode->union_code : NULL;
        $this->from_dest = $this->from_dest;
        ($this->from_type == 'VEN') ? Yii::$app->general->validateGlobalData($this, 'from_dest', 'customer_code') : NULL;
        $this->status = ($this->in_use == '1') ? 2 : (($this->from_type == 'VEN') ? 0 : '-1');
        if ($this->from_type != 'VEN') {
            if (!empty($this->fromSlocCode)) {
                $this->from_dest = $this->fromSlocCode->store_location_code;
                $f_slt_code = $this->fromStoreLocCode->storeLocType->slt_code;
                $f_slt_name = $this->fromStoreLocCode->storeLocType->slt_name;
                if ($this->from_type != $f_slt_code && $this->from_type != $f_slt_name) {
                    $this->addError('from_type', Yii::t('app/validation', 'From Type and From Dest does not match.'));
                    return false;
                } else {
                    $this->from_type = (string) $f_slt_code;
                }
            }
        }
        if (!empty($this->toSlocCode)) {
            $this->to_dest = $this->toSlocCode->store_location_code;
            $t_slt_code = $this->toStoreLocCode->storeLocType->slt_code;
            $t_slt_name = $this->toStoreLocCode->storeLocType->slt_name;
            if ($this->to_type != $t_slt_name && $this->to_type != $t_slt_code) {
                $this->addError('from_type', Yii::t('app/validation', 'To Type and To Dest does not match.'));
                return false;
            } else {
                $this->to_type = (string) $t_slt_code;
                $this->status = ($t_slt_code == 3) ? 2 : $this->status;
            }
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPreviousEntry() {
        return $this->find()->where(['to_type' => $this->from_type, 'to_dest' => $this->from_dest, 'asset_detail_code' => $this->asset_detail_code])->one();
    }

    public function getQtySum($get_type) {
        $type = $get_type . '_type';
        $dest = $get_type . '_dest';
        $record = $this->find()
                ->select(['qty' => 'ISNULL(SUM(qty),0)'])
                ->where([$type => $this->from_type, $dest => $this->from_dest, 'asset_code' => $this->asset_code])
                ->one();
        return $record->qty;
    }

    public function getSerialNoRecords() {
        $data = $this->find()
                ->where(['to_type' => $this->from_type, 'to_dest' => $this->from_dest, 'asset_code' => $this->asset_code, 'status' => [0, 2]])
                ->all();
        $records = ArrayHelper::map($data, function($data, $key) {
                    return $data['serial_number'] . '===' . $data['asset_transaction_code'];
                }, 'serial_number');
        return $records;
    }

    public function checkUnique($attribute, $params) {
        $data = 0;
        if (Yii::$app->general->getforeignkey($this->assetCode, 'is_serial_number') == '1') {
            $query = $this->find()->where(['serial_number' => $this->serial_number, 'asset_code' => $this->asset_code, 'from_type' => $this->from_type, 'from_dest' => $this->from_dest, 'to_type' => $this->to_type, 'to_dest' => $this->to_dest, 'transaction_date' => $this->transaction_date]);
            if (!empty($this->asset_transaction_code)) {
                $query->andWhere(['<>', 'asset_transaction_code', $this->asset_transaction_code]);
            }
            $data = $query->count();
        }
        if ($data != 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Asset/Serial No. has already been taken.'));
        }
    }

    public function getVENEntry() {
        return $this->find()->where(['from_type' => $this->from_type, 'asset_detail_code' => $this->asset_detail_code])->count();
    }

    public function getSrNoAssets($ref_code, $slocType, $concatSrNo = false, $is_spare = false) {
        $details = TblAssetTransaction::find()->select(['tbl_asset_transaction.asset_code', 'tbl_asset_transaction.serial_number'])
                ->innerJoin('tbl_asset_master', 'tbl_asset_master.asset_code = tbl_asset_transaction.asset_code')
                ->joinWith(['toStoreLocCode', 'assetDetail'])
                ->where(['tbl_asset_transaction.status' => [2], 'tbl_store_location.store_location_type' => $slocType, 'tbl_store_location.reference_code' => $ref_code]);
        if ($is_spare) {
            $details = $details->andWhere(['tbl_asset_master.is_spare' => 0]);
        }
        $details = $details->orderBy(['tbl_asset_detail.put_to_use_date' => SORT_DESC])
                ->all();

        if ($concatSrNo) {
            $value = ArrayHelper::map($details, function ($value) {
                        return $value->asset_code . '##' . $value->serial_number;
                    }, function ($value) {
                        return Yii::$app->general->getforeignkey($value->assetCode, 'asset_name') . ' - ' . $value->serial_number;
                    });
        } else {
            $value = ArrayHelper::map($details, 'asset_code', function ($value) {
                        return Yii::$app->general->getforeignkey($value->assetCode, 'asset_name');
                    });
        }
        return $value;
    }

    public function getAssetSetCode() {
        return $this->hasOne(TblAssetSet::className(), ['sap_code' => 'sap_code', 'store_location_code' => 'to_dest']);
    }

    public function getAssetSet($asset_set_code) {
        return TblAssetTransaction::find()->select(['serial_number', 'asset_code', 'asset_transaction_code', 'qty'])
                        ->joinWith(['assetSetCode'])
                        ->where(['tbl_asset_set.status' => [2, 0], 'tbl_asset_set.asset_set_code' => $asset_set_code])->all();
    }

    public function getInUseSAPCode() {
        return $this->hasOne(TblAssetSet::className(), ['store_location_code' => 'to_dest'])->where(['status' => 2]);
    }

    public function validateDestination($attribute, $params) {
        if (!empty($this->from_type) && !empty($this->to_type)) {
            if (($this->from_type == 3) && ($this->to_type != 2 || $this->from_mcc != $this->to_mcc)) {
                $this->addError('to_mcc', Yii::t('app/validation', 'Cannot Not move to other MCC'));
            } elseif ($this->from_type == 2 && $this->to_type == 3 && $this->from_mcc != $this->to_mcc) {
                $this->addError('to_mcc', Yii::t('app/validation', 'Cannot Not move to other MCC'));
            }
        }
    }

    public function validateOutValidate($attribute, $params) {
        if (!empty($this->from_type) && !empty($this->to_type)) {
            if (($this->from_type == 3) && ($this->to_type == 2 || $this->to_type == 3) && $this->from_mcc != $this->to_mcc) {
                $this->addError('to_mcc', Yii::t('app/validation', 'Cannot Not move to other MCC'));
            } elseif ($this->from_type == 2 && $this->to_type == 3 && $this->from_mcc != $this->to_mcc) {
                $this->addError('to_mcc', Yii::t('app/validation', 'Cannot Not move to other MCC'));
            }
        }
    }

    public function getNewSrNo($asset_code, $ref_code, $spare_code, $type) {
        return $this->find()
                        ->select(['DISTINCT(tbl_asset_transaction.serial_number) as serial_number'])
                        ->innerJoin('tbl_store_location', 'tbl_store_location.store_location_code = tbl_asset_transaction.to_dest and tbl_store_location.store_location_type=tbl_asset_transaction.to_type')
                        ->where(['tbl_asset_transaction.asset_code' => $spare_code, 'tbl_asset_transaction.status' => '0'])
                        ->andWhere(['tbl_store_location.store_location_type' => $type])
                        ->andWhere(['tbl_store_location.reference_code' => $ref_code])
                        ->asArray()->all();
    }

    public function getAttachment() {
        $this->asset_transaction_code = (string) $this->asset_transaction_code;
        return $this->hasOne(TblAttachment::className(), ['module_code' => 'asset_transaction_code']);
    }

    public function getDetailCode() {
        $storeLocationData = TblStoreLocation::find()->select(['store_location_type', 'reference_code'])->where(['store_location_code' => $this->to_dest, 'is_active' => '1'])->one();
        $moduleMapping = ['1' => 'plant', '2' => 'bmc', '3' => 'society'];
        if (!empty($storeLocationData) && isset($moduleMapping[$this->to_type])) {
            $detailCode = TblContactDetails::find()->select('detail_code')->where(['module_code' => $storeLocationData->reference_code, 'module_name' => $moduleMapping[$storeLocationData->store_location_type], 'is_active' => '1', 'is_default' => '1'])->scalar();
            if (!empty($detailCode)) {
                $this->detail_code = $detailCode;
            }
        }
    }
    
    public function getAssetClusterVendorInfo() {
        return $this->hasOne(TblAssetClusterVendorInfo::className(), ['asset_code' => 'asset_code', 'serial_number' => 'serial_number']);
    }

}
