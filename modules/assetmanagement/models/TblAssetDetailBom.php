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
            [['spare_code'], 'checkCode', 'on' => 'importCsv'],
            [['spare_code'], 'checkUnique', 'on' => 'importCsv'],
            [['spare_code'], 'checkIsSerialNumber'],
            [['asset_detail_code'], 'checkCode', 'on' => 'importCsv'],
            [['spare_code'], 'checkUnique', 'on' => 'importCsv'],
            [['spare_code'], 'checkIsSerialNumber', 'on' => 'importCsv'],
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
        $data = $this->find()->where(['asset_detail_code' => $this->asset_detail_code, 'spare_code' => $this->spare_code, 'serial_number' => $this->serial_number])
                ->count();

        if ($data != 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Spare name/Serial Number has already been taken'));
        }
    }

    public function checkCode($attribute) {
        $data = 0;
        if ($attribute == 'spare_code') {
            $data = TblAssetBom::find()
                            ->innerJoin('tbl_asset_master', 'tbl_asset_master.asset_code = tbl_asset_bom.spare_code')
                            ->where(['tbl_asset_bom.spare_code' => $this->spare_code])->count();
        } else {
            $data = TblAssetDetail::find()
                            ->where(['asset_detail_code' => $this->asset_detail_code])->count();
        }
        if ($data == 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Spare code is not exist'));
        }
    }

    public function checkIsSerialNumber($attribute) {
        $data = TblAssetBom::find()
                ->where(['spare_code' => $this->spare_code])
                ->one();
        if (!empty($data) && isset($data->is_serial_number)) {
            if ($data->is_serial_number == 1) {
                if ($this->serial_number == '') {
                    $this->addError('serial_number', Yii::t('app/validation', 'Please enter serial number'));
                }
            } else {
                $this->addError('is_serial_number', Yii::t('app/validation', 'No Serial Number for this Spare'));
            }
        }
    }

}
