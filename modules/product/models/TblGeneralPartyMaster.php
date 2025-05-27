<?php

namespace app\modules\product\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblBmcChillerInfo;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\transporter\models\TblVehicleKmInfo;
use app\modules\transporter\models\TblVehicleMaster;
use Yii;

/**
 * This is the model class for table "tbl_general_party_master".
 *
 * @property integer $general_party_master_code
 * @property string $party_name
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $ref_code
 * @property string $party_type
 * @property string $party_code
 * @property integer $is_product_sale
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblGeneralPartyMaster extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_general_party_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['party_name', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'ref_code', 'party_type', 'party_code', 'is_product_sale', 'is_active', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['is_active', 'is_product_sale'], 'default', 'value' => 1],
            [['party_name'], 'string', 'max' => 225],
            [['union_code', 'plant_code', 'mcc_plant_code', 'ref_code'], 'required', 'except' => ['importCsv']],
            [['bmc_code', 'party_type', 'party_name'], 'required'],
            [['party_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'general_party_type');
                }, 'on' => 'importCsv'],
            [['bmc_code'], 'assignAutoData', 'skipOnError' => true, 'on' => 'importCsv'],
            [['bmc_code'], 'unique', 'targetAttribute' => ['union_code', 'party_type', 'party_code'], 'message' => 'The combination of Union, Party Type and Party Code has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'general_party_master_code' => Yii::t('app', 'General Party Master Code'),
            'party_name' => Yii::t('app', 'Party Name'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'party_type' => Yii::t('app', 'Party Type'),
            'party_code' => Yii::t('app', 'Party Code'),
            'is_product_sale' => Yii::t('app', 'Is Product Sale'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function assignAutoData($attribute, $params) {
        if (empty($this->getErrors())) {
            if (!empty($this->ref_code) || (!empty($this->party_code))) {
                $bmcModel = new TblDcsBmc();
                $bmcModel->bmc_code = $this->bmc_code;
                $bmcData = $bmcModel->bmcData(TRUE);
                if (!empty($bmcData[0])) {
                    $this->union_code = $bmcData[0]->union_code;
                    $this->plant_code = $bmcData[0]->plant_code;
                    $this->mcc_plant_code = $bmcData[0]->mcc_plant_code;
                    $this->bmc_code = $bmcData[0]->bmc_code;
                    if ($this->party_type == 'EMPLOYEE') {
                        $this->ref_code = !empty($this->ref_code) ? $this->ref_code : $this->party_code;
                        $this->party_code = NULL;
                    } else if ($this->party_type == 'VEHICLE') {
                        $vehicleData = TblVehicleMaster::find()->where(['or', ['parsing_no' => $this->party_code], ['parsing_no' => $this->ref_code]])->one();
                        if (!empty($vehicleData)) {
                            $vehicleKmInfomodel = new TblVehicleKmInfo();
                            $vehicleKmInfomodel->vehicle_code = $vehicleData->vehicle_code;
                            $mappedData = $vehicleKmInfomodel->getLatestVehicleData($this->bmc_code);
                            if (empty($mappedData)) {
                                $this->addError('party_code', Yii::t('app/validation', $this->getAttributeLabel('party_code') . ' is not mapped.'));
                            }
                            $this->ref_code = $vehicleData->parsing_no;
                            $this->party_code = $vehicleData->vehicle_code;
                        } else {
                            $this->addError('party_code', Yii::t('app/validation', $this->getAttributeLabel('party_code') . ' is invalid.'));
                        }
                    } else if ($this->party_type == 'CHILLER') {
                        $bmcChillerInfoData = TblBmcChillerInfo::find()->where(['or', ['sap_vendor_code' => $this->party_code], ['sap_vendor_code' => $this->ref_code]])->one();
                        if (!empty($bmcChillerInfoData)) {
                            $this->party_code = $bmcChillerInfoData->chiller_info_code;
                            $this->ref_code = $bmcChillerInfoData->sap_vendor_code;
                        } else {
                            $this->addError('party_code', Yii::t('app/validation', $this->getAttributeLabel('party_code') . ' is invalid.'));
                        }
                    }
                } else {
                    $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' is invalid.'));
                }
            } else {
                $this->addError('party_code', Yii::t('app/validation', $this->getAttributeLabel('party_code') . ' cannot be blank.'));
            }
        }
    }

    public function validateGenaralPartyCode($party, $bmc) {
        $data = $this->find()->select('general_party_master_code')->where(['bmc_code' => $bmc, 'is_active' => 1])->andWhere(['or', ['CAST(general_party_master_code as varchar)' => $party], ['ref_code' => $party]])->all();
        return !empty($data) && count($data) == 1 ? $data[0]->general_party_master_code : '';   
    }
}
