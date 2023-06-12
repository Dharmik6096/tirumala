<?php

namespace app\modules\assetmanagement\models;

use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_asset_bom".
 *
 * @property integer $asset_bom_code
 * @property integer $asset_code
 * @property string $spare_code
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
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblAssetBom extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_bom';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_serial_number', 'qty', 'is_active', 'originating_type'], 'safe'],
            [['union_code', 'spare_code', 'created_at', 'updated_at', 'asset_code', 'is_serial_number', 'qty', 'is_active', 'originating_type'], 'safe'],
            [['is_active'], 'default', 'value' => 1],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['spare_code', 'union_code', 'qty'], 'required'],
            [['spare_code'], 'checkUnique', 'on' => 'create', 'except' => ['update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'asset_bom_code' => Yii::t('app', 'Asset Bom Code'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'spare_code' => Yii::t('app', 'Spare Name'),
            'union_code' => Yii::t('app', 'Union'),
            'is_serial_number' => Yii::t('app', 'Is Serial Number'),
            'qty' => Yii::t('app', 'Qty'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
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

    public function getAssetData() {
        return $this->find()
                        ->where(['spare_code' => $this->spare_code, 'asset_code' => $this->asset_code])
                        ->one();
    }

    public function getBomList($asset_code) {
        return $this->find()
                        ->select(['tbl_asset_bom.spare_code', 'tbl_asset_master.asset_name'])
                        ->innerJoin('tbl_asset_master', 'tbl_asset_master.asset_code = tbl_asset_bom.spare_code')
                        ->where(['tbl_asset_bom.asset_code' => $asset_code])->asArray()->all();
    }

    public function checkUnique($attribute) {
        $data = $this->find()->where(['spare_code' => $this->spare_code, 'asset_code' => $this->asset_code])
                ->count();
        if ($data != 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Spare Code has already been taken.'));
        }
    }

}
