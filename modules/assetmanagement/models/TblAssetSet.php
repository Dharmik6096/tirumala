<?php

namespace app\modules\assetmanagement\models;

use Yii;
use app\modules\assetmanagement\models\TblStoreLocation;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_asset_set".
 *
 * @property integer $asset_set_code
 * @property string $sap_code
 * @property string $sloc_code
 * @property string $store_location_type
 * @property string $reference_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $status
 * @property integer $is_active
 */
class TblAssetSet extends \app\models\ChildModel {

    public $from_plant, $from_mcc, $from_bmc, $from_dcs, $from_type, $from_dest;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_set';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sap_code', 'sloc_code', 'store_location_type', 'reference_code', 'union_code', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'store_location_code', 'from_plant', 'from_mcc', 'from_bmc', 'from_dcs', 'from_type', 'from_dest'], 'safe'],
            [['status', 'is_active'], 'integer'],
            [['sap_code', 'from_dest'], 'required', 'on' => ['create_single']],
            [['from_type'], 'required', 'on' => ['create_main']],
            [['sap_code'], 'uniqueValidate', 'on' => ['create_single', 'create_dcs', 'update_dcs', 'update_single']],
            [['from_plant', 'from_mcc'], 'required', 'when' => function ($model) {
            return $model->from_type == '3' || $model->from_type == '2';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tblassetset-from_type').val() == '3' || $('#tblassetset-from_type').val() == '2'; 
          }", 'on' => ['create_main']],
            [['sap_code'], 'statusValidate', 'on' => ['create_single', 'create_dcs', 'update_dcs', 'update_single']],
            [['sap_code'], 'updateStatus', 'on' => ['update_single']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'asset_set_code' => Yii::t('app', 'EIPL Code'),
            'sap_code' => Yii::t('app', 'SAP Code'),
            'sloc_code' => Yii::t('app', 'SLOC Code'),
            'store_location_type' => Yii::t('app', 'Store Location Type'),
            'reference_code' => Yii::t('app', 'Reference Code'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'is_active' => Yii::t('app', 'Is Active'),
            'from_type' => Yii::t('app', 'Store Location Type'),
            'from_dest' => Yii::t('app', 'Store Location'),
            'from_plant' => Yii::t('app', 'Plant'),
            'from_mcc' => Yii::t('app', 'MCC'),
            'from_bmc' => Yii::t('app', 'BMC'),
            'from_dcs' => Yii::t('app', 'DCS'),
        ];
    }

    public function getStoreLocCode() {
        return $this->hasOne(TblStoreLocation::className(), ['store_location_code' => 'store_location_code']);
    }

    public function uniqueValidate($attribute, $params) {
        if (!empty($this->sap_code)) {
            $existData = TblAssetSet::find()
                    ->where(['sap_code' => $this->sap_code, 'status' => [-1, 0, 2]])
                    ->andFilterWhere(['!=', 'asset_set_code', $this->asset_set_code])
                    ->one();
            if (!empty($existData)) {
                $this->addError($attribute, Yii::t('app', 'SAP Code ' . $this->sap_code . ' for ' . Yii::$app->general->getforeignkey($this->storeLocCode, 'store_location_name') . ' has already been taken.'));
            }
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getSapCodeList($union) {
        $data = $this->find()
                ->select(['sap_code'])
                ->where(['union_code' => $union, 'status' => [-1, 0, 2]])
                ->all();
        $value = [];
        if (!empty($data)) {
            $value = ArrayHelper::map($data, 'sap_code', function($data) {
                        return Yii::t('app', $data->sap_code);
                    });
        }
        return $value;
    }

    public function getAssetSAPCodeList($code, $slocType) {
        $data = $this->find()
                ->select(['sap_code'])
                ->where(['status' => [-1, 0, 2], 'store_location_type' => $slocType])
                ->andWhere(['reference_code' => $code])
                ->all();
        $value = [];
        if (!empty($data)) {
            $value = ArrayHelper::map($data, 'sap_code', 'sap_code');
        }
        return $value;
    }

    public function getSapTransaction() {
        $check_data = TblAssetSet::find()
                ->where(['store_location_type' => $this->store_location_type, 'store_location_code' => $this->store_location_code, 'status' => [2]])
                ->andWhere(['!=', 'sap_code', $this->sap_code])
                ->count();
        $check_data += TblAssetTransaction::find()
                ->where(['from_type' => $this->store_location_type, 'from_dest' => $this->store_location_code, 'status' => [2]])
                ->andWhere(['!=', 'sap_code', $this->sap_code])
                ->count();
        return $check_data;
    }

    public function statusValidate($attribute, $params) {
        $check_data = $this->getSapTransaction();
        if ($check_data > 0 && $this->status == 2) {
            $this->addError($attribute, Yii::t('app', 'Other Asset Set already availabe on destination ' . Yii::$app->general->getmultiforeignkey($this->storeLocCode, ['storeLocType'], 'slt_name') . '-' . Yii::$app->general->getforeignkey($this->storeLocCode, 'store_location_name') . ' '));
        }
    }

    public function updateStatus($attribute, $params) {
        $check_data = $this->getSapTransaction();
        if ($check_data > 0) {
            $this->addError($attribute, Yii::t('app', 'Other Asset Set already availabe on destination ' . Yii::$app->general->getmultiforeignkey($this->storeLocCode, ['storeLocType'], 'slt_name') . '-' . Yii::$app->general->getforeignkey($this->storeLocCode, 'store_location_name') . ' '));
        }
    }

}
