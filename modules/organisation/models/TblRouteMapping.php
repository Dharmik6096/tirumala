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
            [['union_code', 'route_code', 'route_name', 'to_dest', 'route_type', 'morning_start_time', 'morning_end_time', 'evening_start_time', 'evening_end_time', 'route_length_kms', 'capacity', 'vehicle_type_code', 'valid_from'], 'required'],
            [['morning_start_time', 'morning_end_time', 'route_name', 'union_code', 'local_name', 'evening_start_time', 'evening_end_time', 'route_type', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by'], 'string'],
            [['capacity', 'vehicle_type_code', 'is_active'], 'integer'],
            [['route_length_kms'], 'number', 'min' => 0, 'message' => Yii::t('app/validation', 'Route Length Kms must be greater than 0.')],
            [['morning_end_time'], 'morningTimeValidate'],
            [['evening_end_time'], 'eveningTimeValidate'],
            [['created_at', 'updated_at', 'unit', 'valid_from'], 'safe'],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            ['to_dest', 'compare', 'compareAttribute' => 'from_dest', 'operator' => '!=', 'message' => 'Source and destination can not be same'],
//            [['route_name'], function ($attribute, $params) {
//            Yii::$app->general->validateName($this, $attribute, $params);
//        }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
            Yii::$app->general->vaildateLocalField($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['route_code'], 'string', 'min' => 1],
            [['route_code'], 'string', 'max' => 8],
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

    public function getDestinations($route_type, $union_code, $route_dest_type = 'to') {
        $results = '';
        $subQuery = (new Query())
                ->select('*')
                ->from('tbl_route_mapping_sources rms');

        //echo $route_type; echo $route_dest_type; exit;

        $plant_quey = (new Query())->select(['plant_code AS code', 'name', new Expression(" 'Plant' as tname")])->from('tbl_plant p')->where(['union_code' => $union_code, 'is_active' => 1])->createCommand()->rawSql;
        $mcc_query = (new Query())->select(['mcc_plant_code AS code', 'name', new Expression("'MCC' as tname")])->from('tbl_mcc_plant t')->where(['union_code' => $union_code, 'is_active' => 1]);
        switch (1) {
            case ($route_type == 'Can' && $route_dest_type == 'from'):
                $dcs_sub_query = $subQuery->where('t.dcs_code = rms.from_dest');
                $results = (new Query())->select(['dcs_code AS code', 'dcs_name AS name', new Expression("'Society' as tname")])->from('tbl_dcs t')->where(['union_code' => $union_code, 'is_active' => 1])->andWhere(['not exists', $dcs_sub_query])->all();
                return $results;

            case ($route_type == 'Tanker' && $route_dest_type == 'from'):
                $bmc_sub_query = $subQuery->where('b.bmc_code = rms.from_dest and rms.from_type=\'bmc\'');
                $bmc_query = (new Query())->select(['bmc_code AS code', 'bmc_name AS name', new Expression(" 'BMC' as tname")])->from('tbl_dcs_subcenter_bmc_info b')->where(['union_code' => $union_code, 'is_active' => 1])->andWhere(['not exists', $bmc_sub_query])->createCommand()->rawSql;
                $mcc_sub_query = $subQuery->where('t.mcc_plant_code = rms.from_dest and rms.from_type=\'mcc\'');
                $results = $mcc_query->andWhere(['not exists', $mcc_sub_query])->union($bmc_query)->all();
                break;

            case ($route_type == 'Can' && $route_dest_type == 'to'):
                $bmc_query = (new Query())->select(['bmc_code AS code', 'bmc_name AS name', new Expression(" 'BMC' as tname")])->from('tbl_dcs_subcenter_bmc_info b')->where(['union_code' => $union_code, 'is_active' => 1])->createCommand()->rawSql;
                $results = $mcc_query->union($plant_quey)->union($bmc_query)->all();
                break;

            case ($route_type == 'Tanker' && $route_dest_type == 'to'):
                $results = $mcc_query->union($plant_quey)->all();
                break;
            default:
        }
        //var_dump($results); exit;
        return $results;
    }

    public function getDestinationName($module, $code) {
        switch ($module) {
            case 'society':
                $name = TblDcs::find()->select('dcs_name')->where(['dcs_code' => $code])->one();
                $name = !empty($name->dcs_name) ? $name->dcs_name : 'N/A';
                break;
            case 'plant':
                $name = TblPlant::find()->select('name')->where(['plant_code' => $code])->one();
                $name = !empty($name->name) ? $name->name : 'N/A';
                break;
            case 'mcc':
                $name = TblMccPlant::find()->select('name')->where(['mcc_plant_code' => $code])->one();
                $name = !empty($name->name) ? $name->name : 'N/A';
                break;
            case 'bmc':
                $name = TblDcsBmc::find()->select('bmc_name')->where(['bmc_code' => $code])->one();
                $name = !empty($name->bmc_name) ? $name->bmc_name : 'N/A';
                break;
            default :
                $name = '';
        }
        return $name;
    }

    public function getCode() {
        return $this->route_code;
        $data = $this->find()->select(["MAX(CONVERT(bigint,route_code)) as route_code"])->one();
        return str_pad(((int) $data['route_code'] + 1), 8, '0', STR_PAD_LEFT);
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

    public function getRoutes($unionCode) {
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

        $route = ArrayHelper::map($route, 'route_code', 'route_name');
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

}
