<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
//use app\modules\globalmaster\models\TblCapacity;
use yii\db\Query;
use yii\db\Expression;
use \app\modules\globalmaster\models\TblUnitConversions;
use app\modules\organisation\models\TblRouteMappingSources;
use yii\helpers\ArrayHelper;
use app\modules\syncutility\models\TblSentbox;
use app\modules\details\models\TblContactDetails;

//use app\modules\globalmaster\models\TblVehicleType;
/**
 * This is the model class for table "tbl_route_mapping".
 *
 * @property string $route_code
 * @property integer $capacity
 * @property string $morning_start_time
 * @property string $morning_end_time
 * @property double $route_length_kms
 * @property string $route_name
 * @property string $union_code
 * @property integer $vehicle_type_code
 * @property string $local_name
 * @property string $evening_start_time
 * @property string $evening_end_time
 * @property string $route_type
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 *
 * @property TblUnions $unionCode
 */
class TblRouteMapping extends \app\models\ChildModel {

    public $unit;
    public $is_sentbox;
    public $firstname, $mobile_no;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_route_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['route_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'route_type');
                }, 'on' => 'importCsv'],
            [['capacity'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'capacity');
                }, 'on' => 'importCsv'],
            [['vehicle_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'vehicle_type_code');
                }, 'on' => 'importCsv'],
            [['union_code', 'route_name', 'to_dest', 'route_type', 'morning_start_time', 'morning_end_time', 'evening_start_time', 'evening_end_time', 'route_length_kms', 'capacity', 'vehicle_type_code'], 'required'],
            [['valid_from'], 'required', 'except' => ['importCsv']],
            [['morning_start_time', 'morning_end_time', 'route_name', 'union_code', 'local_name', 'evening_start_time', 'evening_end_time', 'route_type', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by'], 'string'],
            [['capacity', 'vehicle_type_code', 'is_active'], 'integer'],
            [['route_length_kms'], 'number', 'min' => 0, 'message' => Yii::t('app/validation', 'Route Length Kms must be greater than 0.')],
            [['morning_end_time'], 'morningTimeValidate'],
            [['evening_end_time'], 'eveningTimeValidate'],
            [['evening_grace_time'], 'graceTimeValidate', 'on' => ['importCsv']],
            [['created_at', 'updated_at', 'unit', 'valid_from', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'route_code_ex', 'ref_code', 'mobile_no', 'firstname', 'emilk_sync_status', 'emilk_sync_timestamp'], 'safe'],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            ['to_dest', 'compare', 'compareAttribute' => 'from_dest', 'operator' => '!=', 'message' => 'Source and destination can not be same'],
//            [['route_name'], function ($attribute, $params) {
//            Yii::$app->general->validateName($this, $attribute, $params);
//        }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
//            [['route_code'], 'string', 'min' => 1],
//            [['route_code'], 'string', 'max' => 8],
//            [['route_code'], 'safe'],
//            [['route_code'], 'unique'],
            [['route_code_ex'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false,],
            ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['sap_route_code'], 'unique', 'targetAttribute' => ['sap_route_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['union_code'], 'importData', 'on' => ['importCsv']],
            [['is_active'], 'default', 'value' => 1, 'on' => ['importCsv']],
            [['mobile_no', 'firstname'], 'required', 'on' => ['importCsv']],
            [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'on' => ['importCsv']],
            [['data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'sap_route_code'], 'safe'],
            [['route_code'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data']],
        ];
    }

    public function morningTimeValidate($attribute, $params) {

        if (!empty($this->morning_start_time) && !empty($this->morning_end_time)) {

            if ($this->morning_end_time < $this->morning_start_time) {
                $this->addError($attribute, Yii::t('app/validation', 'Morning End Time Must be Greater Than Morning Start Time.'));
                return false;
            }
        }
    }

    public function eveningTimeValidate($attribute, $params) {


        if (!empty($this->evening_start_time) && !empty($this->evening_end_time)) {

            if ($this->evening_end_time < $this->evening_start_time) {
                $this->addError($attribute, Yii::t('app/validation', 'Evening End Time Must be Greater Than Evening Start Time.'));
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'route_code' => Yii::t('app', 'Route Code'),
            'capacity' => Yii::t('app', 'Vehicle Capacity(Ltr)'),
            'morning_start_time' => Yii::t('app', 'Morning Start Time'),
            'morning_end_time' => Yii::t('app', 'Morning End Time'),
            'route_length_kms' => Yii::t('app', 'Route Length Kms'),
            'route_name' => Yii::t('app', 'Route Name'),
            'union_code' => Yii::t('app', 'Union'),
            'vehicle_type_code' => Yii::t('app', 'Vehicle Type'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'evening_start_time' => Yii::t('app', 'Evening Start Time'),
            'evening_end_time' => Yii::t('app', 'Evening End Time'),
            'route_type' => Yii::t('app', 'Route Type'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'route_code_ex' => Yii::t('app', 'Route Code Ex'),
            'ref_code' => Yii::t('app', 'Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @inheritdoc
     * @return TblRouteMappingQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblRouteMappingQuery(get_called_class());
    }

    public function getVehicleType() {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }

    public function getDestinations($route_type, $union_code, $route_dest_type = 'to', $routeData = '') {
        $results = '';
        $subQuery = (new Query())
                ->select('*')
                ->from('tbl_route_mapping_sources rms');

//echo $route_type; echo $route_dest_type; exit;

        $plant_quey = (new Query())->select(['plant_code AS code', 'name', new Expression(" 'Plant' as tname"), 'plant_code_ex as ex_code', 'ref_code'])->from('tbl_plant p')->where(['union_code' => $union_code, 'is_active' => 1])->createCommand()->rawSql;
        $mcc_query = (new Query())->select(['mcc_plant_code AS code', 'name', new Expression("'MCC' as tname"), 'mcc_plant_code_ex as ex_code', 'ref_code'])->from('tbl_mcc_plant t')->where(['union_code' => $union_code, 'is_active' => 1]);
        $route_type = strtolower($route_type);
        switch (1) {
            case ($route_type == 'can' && $route_dest_type == 'from'):
                $dcs_sub_query = $subQuery->where('t.dcs_code = rms.from_dest');
                $results = (new Query())->select(['dcs_code AS code', 'dcs_name AS name', new Expression("'Society' as tname"), 'dcs_code_ex as ex_code', 'ref_code'])->from('tbl_dcs t')->where(['union_code' => $union_code, 'is_active' => 1])->andWhere(['not exists', $dcs_sub_query]);
                if (!empty($routeData)) {
                    if (strtolower($routeData->to_type) == 'bmc' && !empty($routeData->to_dest)) {
                        $results->andFilterWhere(['bmc_code' => $routeData->to_dest]);
                    }
                    if (strtolower($routeData->to_type) == 'mcc' && !empty($routeData->to_dest)) {
                        $results->andFilterWhere(['mcc_plant_code' => $routeData->to_dest]);
                    }
                    if (strtolower($routeData->to_type) == 'plant' && !empty($routeData->to_dest)) {
                        $results->andFilterWhere(['plant_code' => $routeData->to_dest]);
                    }
                }
                $results = $results->all();
                return $results;

            case ($route_type == 'tanker' && $route_dest_type == 'from'):
                $bmc_sub_query = $subQuery->where('b.bmc_code = rms.from_dest and rms.from_type=\'bmc\'');
                $bmc_query = (new Query())->select(['bmc_code AS code', 'bmc_name AS name', new Expression(" 'BMC' as tname"), 'bmc_code_ex as ex_code', 'ref_code'])->from('tbl_bmc b')->where(['union_code' => $union_code, 'is_active' => 1])->andWhere(['not exists', $bmc_sub_query])->createCommand()->rawSql;
                $mcc_sub_query = $subQuery->where('t.mcc_plant_code = rms.from_dest and rms.from_type=\'mcc\'');
                $results = $mcc_query->andWhere(['not exists', $mcc_sub_query])->union($bmc_query)->all();
                break;

            case ($route_type == 'can' && $route_dest_type == 'to'):
                $bmc_query = (new Query())->select(['bmc_code AS code', 'bmc_name AS name', new Expression(" 'BMC' as tname"), 'bmc_code_ex as ex_code', 'ref_code'])->from('tbl_bmc b')->where(['union_code' => $union_code, 'is_active' => 1])->createCommand()->rawSql;
                $results = $mcc_query->union($plant_quey)->union($bmc_query)->all();
                break;

            case ($route_type == 'tanker' && $route_dest_type == 'to'):
                $results = $mcc_query->union($plant_quey)->all();
                break;
            default:
        }
//var_dump($results); exit;
        return $results;
    }

    public function getDestinationName($module, $code, $select = '') {
        switch (strtolower($module)) {
            case 'society':
                $selectKey = !empty($select) ? $select : 'dcs_name';
                $name = TblDcs::find()->select($selectKey)->where(['dcs_code' => $code])->one();
                $name = !empty($name->{$selectKey}) ? $name->{$selectKey} : 'N/A';
                break;
            case 'plant':
                $selectKey = !empty($select) ? $select : 'name';
                $name = TblPlant::find()->select($selectKey)->where(['plant_code' => $code])->one();
                $name = !empty($name->{$selectKey}) ? $name->{$selectKey} : 'N/A';
                break;
            case 'mcc':
                $selectKey = !empty($select) ? $select : 'name';
                $name = TblMccPlant::find()->select($selectKey)->where(['mcc_plant_code' => $code])->one();
                $name = !empty($name->{$selectKey}) ? $name->{$selectKey} : 'N/A';
                break;
            case 'bmc':
                $selectKey = !empty($select) ? $select : 'bmc_name';
                $name = TblDcsBmc::find()->select($selectKey)->where(['bmc_code' => $code])->one();
                $name = !empty($name->{$selectKey}) ? $name->{$selectKey} : 'N/A';
                break;
            default :
                $name = '';
        }
        return $name;
    }

    public function getCode() {
        return Yii::$app->general->setKeyPattern($this, 'tbl_route_mapping', 'route_code_ex', 7);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapacityCode() {
        return $this->hasOne(TblCapacity::className(), ['capacity_code' => 'capacity']);
    }

    public function calcCapacity($unit) {
        $capacity = isset($this->capacityCode) ? $this->capacityCode->value : '';
        if (!empty($unit) && !empty($capacity)) {
            $conv_model = new TblUnitConversions();
            return $conv_model->getCovertedValue($capacity, 1, $unit);
        } else {
            return $capacity;
        }
    }

    public function getBmcCode() {
        switch ($this->to_type) {
            case 'plant':
                $plant = TblPlant::findOne($this->to_dest);
                $code = !empty($plant->mccCode->bmcCode->bmc_code) ? $plant->mccCode->bmcCode->bmc_code : '0';
                break;
            case 'mcc':
                $bmc = TblMccPlant::findOne($this->to_dest);
                $code = !empty($bmc->bmcCode->bmc_code) ? $bmc->bmcCode->bmc_code : '0';
                break;
            case 'bmc':
                $code = $this->to_dest;
                break;
            default :
                $code = '0';
        }
        return $code;
    }

    public function getRoutes($unionCode, $concateRef = FALSE) {
        $route = $this->find()->where(['union_code' => $unionCode, 'is_active' => 1]);

        $where_bmc = [];
        if (Yii::$app->session->get('BMC') !== '') {
            $where_bmc['tbl_route_mapping.to_dest'] = explode(',', Yii::$app->session->get('BMC'));
            $where_bmc['tbl_route_mapping.to_type'] = 'bmc';
        }

        $where_mcc = [];
        if (Yii::$app->session->get('MCC') !== '') {
            $where_mcc['tbl_route_mapping.to_dest'] = explode(',', Yii::$app->session->get('MCC'));
            $where_mcc['tbl_route_mapping.to_type'] = 'mcc';
        }
        $route->andWhere(['or', $where_bmc, $where_mcc]);
        $route = $route->all();
        $route = \yii\helpers\ArrayHelper::map($route, 'route_code', function ($value) use($concateRef) {
                    return ($concateRef) ? $value['ref_code'] . ' - ' . $value['route_name'] . ' - ' . strtoupper($value['to_type']) : $value['route_name'] . ' - ' . strtoupper($value['to_type']);
                });
//        $route = ArrayHelper::map($route, 'route_code', 'route_name');
        asort($route, SORT_NATURAL | SORT_FLAG_CASE);
        return $route;
    }

    public function route($bmc_code) {
        $data = $this->find()
                ->where(['to_dest' => $bmc_code])
                ->andWhere(['to_type' => 'bmc'])
                ->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'route_code', 'route_name');
        return $array;
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['route_code' => 'route_code']);
    }

    public function getData() {
        return $this->find()
                        ->where(['route_code' => $this->route_code])
                        ->one();
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
        $sentboxArray = [];
        $this->to_type = trim($this->to_type);
        if ($this->to_type == 'bmc') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->to_dest);
        } else if ($this->to_type == 'mcc') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->to_dest, '');
        }
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
        if(!empty($this->set_master_hierarchy) && $flag == 'INSERT'){
            foreach($this->set_master_hierarchy as $hierarchy) {
                $hierarchy->save();
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function afterDelete() {
        $sentboxArray = [];
        if ($this->to_type == 'bmc') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->to_dest);
        } else if ($this->to_type == 'mcc') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->to_dest, '');
        }
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function routeFromDestination($plant_code, $mcc_code = NULL, $bmc_code = NULL, $concateRef = true, $showName = false, $showSap = false) {
        $data = $this->find()->select(['route_code', 'route_name', 'to_type', 'ref_code', 'sap_route_code'])
                ->where(['to_dest' => $plant_code, 'to_type' => 'plant']);
        !empty($mcc_code) ? $data = $data->orWhere(['to_dest' => $mcc_code, 'to_type' => 'mcc']) : '';
        !empty($bmc_code) ? $data = $data->orWhere(['to_dest' => $bmc_code, 'to_type' => 'bmc']) : '';
        $data = $data->andWhere(['is_active' => 1]);
        $data = $data->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'route_code', function ($value) use($concateRef, $showName, $showSap) {
                    return ($showName) ? $value['route_name'] : (($concateRef) ? (($showSap) ? $value['route_name'] . ' - ' . strtoupper($value['to_type']) . ' - ' . $value['ref_code'] . ' - ' . $value['sap_route_code'] : $value['route_name'] . ' - ' . strtoupper($value['to_type']) . ' - ' . $value['ref_code']) : $value['ref_code'] . ' - ' . $value['route_name'] . ' - ' . strtoupper($value['to_type']));
                });
        return $array;
    }

    public function getTblRouteMappingSources() {
        return $this->hasMany(TblRouteMappingSources::className(), ['route_code' => 'route_code'])->andFilterWhere(['from_type' => $this->from_type]);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'to_dest']);
    }

    public function getActiveMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'to_dest', 'union_code' => 'union_code'])->andOnCondition(['is_active' => 1]);
    }

    public function getActivePlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'to_dest', 'union_code' => 'union_code'])->andOnCondition(['is_active' => 1]);
    }

    public function getActiveBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['union_code' => 'union_code'])->andOnCondition(['or',['bmc_code' => $this->to_dest],['ref_code' => $this->to_dest]])->andOnCondition(['is_active' => 1]);
    }

    public function importData($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->valid_from = date('Y-m-d');
            $this->route_name = ucwords($this->route_name);
            $plant = Yii::$app->general->getforeignkey($this->activePlantCode, 'plant_code');
            $mcc = Yii::$app->general->getforeignkey($this->activeMccCode, 'mcc_plant_code');
            $bmc = Yii::$app->general->getforeignkey($this->activeBmcCode, 'bmc_code');
            $this->to_type = strtolower($this->to_type);
            if($this->to_type == 'bmc' && !empty($bmc)){
                $this->to_dest = $bmc;
            }
            $type_value = ['bmc', 'mcc', 'plant'];
            if (!in_array(strtolower($this->to_type), $type_value)) {
                $this->addError($attribute, "Please Enter Valid To Type");
            }
            if (strtolower($this->route_type) == 'tanker') {
                if (strtolower($this->to_type) == 'bmc') {
                    $this->addError($attribute, "Please Enter Valid To Type");
                } else if (strtolower($this->to_type) == 'plant' && $this->to_dest != $plant) {
                    $this->addError($attribute, "Please Enter Valid To Dest.");
                } else if (strtolower($this->to_type) == 'mcc' && $this->to_dest != $mcc) {
                    $this->addError($attribute, "Please Enter Valid To Dest.");
                }
            } else {
                if (strtolower($this->to_type) == 'bmc' && $this->to_dest != $bmc) {
                    $this->addError($attribute, "Please Enter Valid To Type");
                } else if (strtolower($this->to_type) == 'plant' && $this->to_dest != $plant) {
                    $this->addError($attribute, "Please Enter Valid To Dest.");
                } else if (strtolower($this->to_type) == 'mcc' && $this->to_dest != $mcc) {
                    $this->addError($attribute, "Please Enter Valid To Dest.");
                }
            }
//            if (!empty($this->mobile_no)) {
//                $contactModel = new TblContactDetails;
//                $data = $contactModel->find()->where(['or', ['mobile_no' => $this->mobile_no], ['mobile_no' => \Yii::$app->general->encryptData($this->mobile_no)]])
//                                ->andWhere(['<>', 'module_code', $this->route_code])
//                                ->andWhere(['is_active' => 1])->one();
//                if (!empty($data)) {
//                    $this->addError($attribute, Yii::t('app/validation', 'Mobile No has already been taken.'));
//                }
//            }
        }
    }

    public function setChildTable($model, &$modelSave, &$errors) {
        $model->route_code = $this->getCode();
        $contactDetails = new TblContactDetails;
        $contactDetails->firstname = $model->firstname;
        $contactDetails->mobile_no = $model->mobile_no;
        $contactDetails->form_validation_type = 'route-import';
        $contactDetails->setModel('routeMapping', $model->route_code);
        if (!$contactDetails->validate()) {
            $errors[] = $contactDetails->getErrors();
        }
        array_push($modelSave, $contactDetails);
    }

    public function setChildTableSaveDelete(&$model, &$modelSave, &$deleteModel, $unlink_files, $attachments, $masterdoc, $errors) {

        //
    }

    public function graceTimeValidate($attribute, $params) {
        if (!empty($this->morning_grace_time) && !empty($this->evening_grace_time)) {
            if ($this->evening_grace_time < $this->morning_grace_time) {
                $this->addError($attribute, Yii::t('app/validation', 'Evening Grace Time Must be Greater Than Morning Grace Time.'));
                return false;
            }
        }
    }

    public function getDcsBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'to_dest']);
    }

    public function getRouteData($ref_code_check = FALSE) {
        if ($ref_code_check) {
            $data = $this->find()
                    ->where(['or', ['route_code' => $this->route_code], ['ref_code' => $this->route_code]])
                    ->andWhere(['is_active' => 1])
                    ->all();
            $data = (count($data) == 1) ? $data : [];
        } else {
            $data = $this->find()
                    ->where(['route_code' => $this->route_code])
                    ->one();
        }
        return $data;
    }

    public function getUserList($routeCode) {
        $data = $this->find()
                ->select(['u.user_code', 'u.name'])
                ->distinct()
                ->innerJoin('tbl_dcs d', '(tbl_route_mapping.to_type = :typeBmc AND tbl_route_mapping.to_dest = d.bmc_code) 
                     OR (tbl_route_mapping.to_type = :typeMcc AND tbl_route_mapping.to_dest = d.mcc_plant_code)', [
                    ':typeBmc' => 'bmc',
                    ':typeMcc' => 'mcc'
                ])
                ->innerJoin('tbl_user_organization_mapping om', 'om.organization_code = d.dcs_code')
                ->innerJoin('user u', 'u.user_code = om.user_id')
                ->where(['tbl_route_mapping.route_code' => $routeCode])
                ->andWhere(['om.organization_type' => 'DCS'])
                ->andWhere(['u.login_type' => 'route_supervisor'])
                ->asArray()
                ->all();

        return $data;
    }

}
