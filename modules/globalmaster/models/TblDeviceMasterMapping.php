<?php

namespace app\modules\globalmaster\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblDeviceMaster;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblPlantDockMapping;

/**
 * This is the model class for table "tbl_device_master_mapping".
 *
 * @property string $device_mapping_code
 * @property string $device_master_code
 * @property string $applicability_code
 * @property string $applicability_type
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 */
class TblDeviceMasterMapping extends \app\models\ChildModel {

    public $device_id, $center_type, $center_code;
    public $union_code, $plant_code, $mcc_plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_master_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['device_id', 'center_type', 'center_code', 'wef_date', 'dock_no'], 'safe'],
                [['center_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'applicability_type');
                }, 'on' => 'importMapping'],
                [['applicability_type', 'applicability_code', 'wef_date'], 'required', 'on' => ['create']],
                [['device_id', 'center_type', 'center_code', 'wef_date'], 'required', 'on' => ['importMapping']],
                [['center_code'], 'validateCollectionCode', 'on' => ['importMapping']],
                [['device_id'], 'setImportVariables', 'on' => ['importMapping']],
                [['device_master_code', 'wef_date', 'applicability_code', 'applicability_type'], 'on', 'except' => ['importMapping']],
                [['wef_date', 'created_at', 'updated_at'], 'safe'],
                [['wef_date'], 'convertDateDot', 'on' => 'importMapping'],
                [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => 'importMapping'],
                [['wef_date'], 'convertDate', 'on' => 'importMapping'],
                [['originating_type'], 'integer'],
                [['applicability_type', 'created_by', 'updated_by', 'applicability_code', 'originating_org_type', 'originating_org_code'], 'safe'],
                ['device_master_code', 'unique', 'skipOnError' => true, 'targetAttribute' => ['device_master_code', 'wef_date'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                    return empty($model->getErrors());
                }],
                ['applicability_code', 'unique', 'skipOnError' => true, 'targetAttribute' => ['applicability_code', 'wef_date'], 'message' => Yii::t('app/validation', 'Center Code has already been taken.'), 'when' => function ($model) {
                    return empty($model->getErrors());
                }],
                [['dock_no'], 'required', 'when' => function($model) {
                    return $model->applicability_type == 3;
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tbldevicemastermapping-applicability_type').val() == '3'; 
                }"],
                [['applicability_code'], 'unique', 'skipOnError' => true, 'targetAttribute' => ['dock_no', 'applicability_code', 'device_master_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                    return empty($model->getErrors()) && $model->applicability_type == 3;
                }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'device_mapping_code' => Yii::t('app', 'Device Mapping Code'),
            'device_master_code' => Yii::t('app', 'Device'),
            'applicability_code' => Yii::t('app', 'Applicability Name'),
            'applicability_type' => Yii::t('app', 'Applicability Type'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dock_no' => Yii::t('app', 'Dock No'),
        ];
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicability_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicability_code']);
    }

    public function getDeviceMasterCode() {
        return $this->hasOne(TblDeviceMaster::className(), ['device_master_code' => 'device_master_code']);
    }

    public function getDeviceId() {
        return $this->hasOne(TblDeviceMaster::className(), ['mac_address' => 'device_id']);
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
        }
    }

    public function getDeviceMapping($device) {
        $masterModel = new TblDeviceMaster();
        $masterData = $masterModel->find()->where(['mac_address' => $device])->one();
        $data = [];
        if (!empty($masterData)) {
            $data = $this->find()
                    ->where(['device_master_code' => $masterData->device_master_code])
                    ->andWhere(['<=', 'wef_date', date('Y-m-d')])
                    ->orderBy('wef_date desc')
                    ->one();
        }

        return $data;
    }

    public function setImportVariables() {
        $this->device_master_code = $this->deviceId->device_master_code;
        $this->applicability_type = $this->center_type;
        $this->applicability_code = $this->center_code;
        if ($this->applicability_type != '3') {
            $this->dock_no = NULL;
        }
    }

    public function validateCollectionCode() {
        if (empty($this->deviceId)) {
            $this->addError('device_id', Yii::t('app/validation', 'Invalid Device Id.'));
        }
        if (!empty($this->center_type)) {
            if ($this->center_type == '1') {
                $bmc = TblDcsBmc::find()->select('bmc_code')->where(['or', ['bmc_code' => $this->center_code], ['ref_code' => $this->center_code]])->all();
                if (empty($bmc)) {
                    $this->addError('center_code', Yii::t('app/validation', 'Invalid Center Code.'));
                } else {
                    $this->center_code = !empty($bmc) && count($bmc) == 1 ? $bmc[0]->bmc_code : '';
                }
            } else if ($this->center_type == '2') {
                $dcs = TblDcs::find()->select('dcs_code')->where(['or', ['dcs_code' => $this->center_code], ['ref_code' => $this->center_code]])->all();
                if (empty($dcs)) {
                    $this->addError('center_code', Yii::t('app/validation', 'Invalid Center Code.'));
                } else {
                    $this->center_code = !empty($dcs) && count($dcs) == 1 ? $dcs[0]->dcs_code : '';
                }
            } else if ($this->center_type == '3') {
                $plant = TblPlant::find()->select('plant_code')->where(['or', ['plant_code' => $this->center_code], ['ref_code' => $this->center_code]])->all();
                if (empty($plant)) {
                    $this->addError('center_code', Yii::t('app/validation', 'Invalid Center Code.'));
                } else {
                    $this->center_code = !empty($plant) && count($plant) == 1 ? $plant[0]->plant_code : '';
                }
            }
        } else {
            $this->addError('center_type', Yii::t('app/validation', 'Invalid Center Type.'));
        }
    }

    public function setChildTable($model, &$modelSave) {
//        if (!empty($model->applicability_type) && !empty($model->applicability_code)) {
//            if (in_array($model->applicability_type, [4, 5, 6])) {
//                $simModel = new \app\modules\configuration\models\TblSimDetail();
//                $simDetail = $simModel->find()->where(['dcs_code' => $model->applicability_code])->one();
//
//                if (!empty($simDetail)) {
//                    $data = $this->find()
//                            ->where(['applicability_type' => $model->applicability_type, 'applicability_code' => $model->applicability_code])
//                            ->andWhere(['<=', 'wef_date', date('Y-m-d')])
//                            ->orderBy('wef_date desc')
//                            ->one();
//
//                    if (!empty($data)) {
//                        if ($model->wef_date <= date('Y-m-d')) {
//                            if ($model->wef_date < $data->wef_date) {
//                                $simDetail->device_imei_no = Yii::$app->general->getforeignkey($data->deviceMasterCode, 'mac_address');
//                            } else {
//                                $simDetail->device_imei_no = Yii::$app->general->getforeignkey($model->deviceMasterCode, 'mac_address');
//                            }
//                        }
//                    } elseif ($model->wef_date <= date('Y-m-d')) {
//                        $simDetail->device_imei_no = Yii::$app->general->getforeignkey($model->deviceMasterCode, 'mac_address');
//                    }
//                    array_push($modelSave, $simDetail);
//                }
//            }
//        }
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicability_code']);
    }

    public function getDockNo() {
        return $this->hasOne(TblPlantDockMapping::className(), ['dock_no' => 'dock_no'])->andWhere(['plant_code' => $this->applicability_code]);
    }

}
