<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblRoutes;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;
use app\modules\dcsoperation\models\TblDcsPurchaseRateBased;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;
use app\modules\dcsoperation\models\TblSentboxRatechart;
use yii\helpers\Json;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * This is the model class for table "tbl_dcs_purchase_rate_applicability".
 *
 * @property integer $rate_app_code
 * @property string $wef_date
 * @property string $created_at
 * @property string $deleted_at
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $purchase_rate_code
 * @property string $created_by
 * @property string $dcs_code
 * @property string $shift_code
 * @property string $union_code
 * @property string $updated_by
 * @property string $deleted_by
 * @property integer $is_active
 *
 * @property TblDcsPurchaseRateMaster $purchaseRateCode
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblUnions $unionCode
 * @property User $updatedBy
 */
class TblDcsPurchaseRateApplicabitity extends \app\models\ChildModel {

    public $route_code;
    public $organization;

    /**
     * @inheritdoc
     */
    public $is_sentbox;

    function __construct() {
        parent::__construct();
        $this->is_sentbox = FALSE;
    }

    public static function tableName() {
        return 'tbl_dcs_purchase_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['is_active', 'default', 'value' => 1],
            ['is_download', 'default', 'value' => 0],
            [['wef_date', 'shift_code', 'applicable_code'], 'required'],
            [['applicable_code'], 'checkDuplicate'],
//            [['route_code'], 'required', 'except' => 'applicability'],
            [['purchase_rate_code', 'dcs_code', 'is_active', 'sync_status', 'created_at', 'shift_code', 'deleted_at', 'sync_timestamp', 'updated_at', 'wef_date', 'applicable_code', 'applicable_for'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
//            [['dcs_code'], 'string', 'max' => 9],
//            [['union_code'], 'string', 'max' => 3],
//[['purchase_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsPurchaseRateMaster::className(), 'targetAttribute' => ['purchase_rate_code' => 'purchase_rate_code']],
//[['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
//[['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rate_app_code' => Yii::t('app', 'Rate Apply ID'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs'),
            'shift_code' => Yii::t('app', 'Shift'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'route_name' => Yii::t('app', 'Route Name'),
            'applicable_code' => Yii::t('app', 'Code'),
            'applicable_for' => Yii::t('app', 'For'),
            'mcc_name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseRateCode() {
        return $this->hasOne(TblDcsPurchaseRate::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy() {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateApplicabitityQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsPurchaseRateApplicabitityQuery(get_called_class());
    }

    /**
     * Return selected dcs/subcenter/route array in dcs head load mapping
     * @param type $headLoadCode
     * @param type $unionCode
     * @return type
     */
    public function getPurchaseRateApplicability($purchaseRateCode, $unionCode) {

        $routes = new TblRoutes;
        $routes = $routes->getRoutes($unionCode);

        $selected = $this->find()->joinWith(['dcsCode'])->select('wef_date,shift_code,tbl_dcs_purchase_rate_applicability.dcs_code,tbl_dcs_purchase_rate_applicability.union_code')->where(['purchase_rate_code' => $purchaseRateCode, 'tbl_dcs_purchase_rate_applicability.is_active' => 1])->all();
        $organizations = [];
        $selectedRoute = [];
        $selectedOrg = [];
        $orgFlag = 0;
        $wefDate = date('d-m-Y');
        $shiftCode = '';
        foreach ($selected as $row) {
            if (!empty($row->dcs_code)) {
                $routeCode = $row->dcsCode->route_code;
                $orgFlag = 0;
                $selectedOrg[$row->dcs_code] = ['selected' => 'selected'];
            }
            $wefDate = $row->wef_date;
            $selectedRoute[$routeCode] = ['selected' => 'selected'];
            $shiftCode = $row->shift_code;
            $returnArray = $this->getAllOrg($routeCode, $orgFlag);
            $organizations = array_merge($organizations, $returnArray);
        }
        return ['routes' => $routes, 'shiftCode' => $shiftCode, 'orgFlag' => $orgFlag, 'wefDate' => $wefDate, 'selectedRoutes' => $selectedRoute, 'selectedOrganization' => $selectedOrg, 'selectedAllOrg' => $organizations];
    }

//    public function getCode() {
//        $val = (new \yii\db\Query)
//                ->select("MAX(CAST(trim(rate_app_code) AS UNSIGNED)) as rate_app_code")
//                ->from('tbl_dcs_purchase_rate_applicability')
//                ->one();
//        $number = (int) $val['rate_app_code'] + 1;
//        return str_pad($number, 2, '0', STR_PAD_LEFT);
//    }

    public function getCode() {
        $data = $this->find()->select(["convert(int,MAX(rate_app_code)) as rate_app_code"])->one();
        return (int) $data['rate_app_code'] + 1;
    }

    public function checkDuplicate($attribute, $params) {
        if (is_array($this->applicable_code)) {
            $shift = strtolower($this->purchaseRateCode->shiftApplicability->shift);
            $wef_date = date('Y-m-d', strtotime($this->wef_date));
            if ($shift == 'all') {
                $shiftarray = ['all', 'morning', 'evening'];
            } else {
                $shiftarray = ['all', $shift];
            }
            $check = $this->find()->select(['tbl_dcs_purchase_rate_applicability.applicable_code', 'tbl_dcs_purchase_rate.shift_applicability', 'tbl_dcs_purchase_rate.purchase_rate_code', 'tbl_dcs_purchase_rate_applicability.applicable_for'])->joinWith(['purchaseRateCode.shiftApplicability'])
                    ->where(['or',
                        ['tbl_dcs_purchase_rate_applicability.purchase_rate_code' => $this->purchase_rate_code,
                            'tbl_dcs_purchase_rate_applicability.applicable_code' => $this->applicable_code, 'tbl_dcs_purchase_rate_applicability.wef_date' => $wef_date],
                        ['tbl_dcs_purchase_rate_applicability.applicable_code' => $this->applicable_code,
                            'convert(date, tbl_dcs_purchase_rate.wef_date, 103)' => $wef_date,
                            'tbl_dcs_purchase_rate.originating_org_type' => 'UNION',
                            'tbl_dcs_purchase_rate.originating_org_code' => Yii::$app->session->get('organizations_code'),
                            'tbl_shift.shift' => $shiftarray]
                    ])
                    ->andWhere(['applicable_for' => $this->applicable_for])
                    ->all();
            if (count($check) > 0) {
                $i = 0;
                foreach ($check as $data) {
                    $name = Yii::$app->general->getforeignkey($data->customerMasterCode, 'customer_name');
                    if ($data->applicable_for == 'PLANT') {
                        $name = Yii::$app->general->getforeignkey($data->plantCode, 'name');
                    } else if ($data->applicable_for == 'MCC') {
                        $name = Yii::$app->general->getforeignkey($data->mccPlantCode, 'name');
                    } else if ($data->applicable_for == 'BMC') {
                        $name = Yii::$app->general->getforeignkey($data->bmcCode, 'bmc_name');
                    }
                    $this->addError('applicable_code[' . $i . ']', $name . ' ' . Yii::t('app/validation', 'applicability already available for given input.'));
                    $i++;
                }
                return FALSE;
            }
        }
    }

    private function getAllOrg($routeCode, $orgFlag) {
        $finalArray = [];
        if ($orgFlag == 0) {
            $dcs = new TblDcs();
            $dcsAry = $dcs->getRouteDcs($routeCode);
            $records = ArrayHelper::map($dcsAry, 'dcs_code', 'dcs_name');
            $finalArray = array_merge($finalArray, $records);
        }

        return $finalArray;
    }

    private function jsonModel($model) {
        $newModel = null;
        $scema = $model->getTableSchema();
        foreach ($model->attributes as $key => $a) {
            $type = $scema->columns[$key]->type;
            if ($type == 'integer') {
                $a = (int) $a;
            } elseif ($type == 'double') {
                $a = (double) $a;
            }
            $new_key = str_replace('_', ' ', $key);
            $new_key = ucwords($new_key);
            $new_key = str_replace(' ', '', $new_key);
            $new_key = lcfirst($new_key);

            $newModel[$new_key] = $a;
        }

        return(object) $newModel;
    }

    public function getDcsPurchaseRateApplicableData($data) {
        return $this->find()
                        ->select(['dprd.rate_type_code as rate_app_code', 'tbl_dcs_purchase_rate_applicability.purchase_rate_code'])
                        ->joinWith(['purchaseRateCode'])
                        ->join('LEFT JOIN', 'tbl_dcs_purchase_rate_details dprd', 'dprd.purchase_rate_code = tbl_dcs_purchase_rate_applicability.purchase_rate_code')
                        ->where(['tbl_dcs_purchase_rate_applicability.is_active' => 1, 'tbl_dcs_purchase_rate_applicability.applicable_code' => $data['appl_code'], 'tbl_dcs_purchase_rate_applicability.applicable_for' => $data['appl_for'], 'tbl_dcs_purchase_rate.shift_applicability' => [3, $data['shift']]])
                        ->andWhere(['<=', 'tbl_dcs_purchase_rate_applicability.wef_date', $this->wef_date])
                        ->andWhere(['dprd.milk_type_code' => $data['milk_type'], 'dprd.milk_quality_type_code' => $data['milk_quality_type_code']])
                        ->orderBy('tbl_dcs_purchase_rate_applicability.wef_date desc')
                        ->one();
    }

    public function SaveRateJson($model, $operation, $sentModel) {
        $ratesentbox = new TblSentboxRatechart();
        $ratesentbox->attributes = $model->attributes;
        $sentModel->entry($model, $operation, $sentModel);
        $ratesentbox->attributes = $sentModel->attributes;
        $ratesentbox->version_no = 'PORTAL';
        $ratesentbox->purchase_rate_type = 'DCS';
        $ratesentbox->save();
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function getCustomerMasterCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

    public function getDcsName() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getPendingApplicability($device_id, $hash_key, $dcs_code, $bmc_code, $mcc_plant_code, $plant_code) {
        $customer_code = TblCustomerMaster::find()->select(['customer_code'])->where(['bmc_code' => $bmc_code]);
        $customer_type = TblCustomerType::find()->select(['customer_type'])->where(['is_organisation' => 0]);

        return $this->find()->select(['tbl_dcs_purchase_rate_applicability.*'])
                        ->leftJoin('tbl_rate_download_ack', "tbl_rate_download_ack.purchase_rate_code=tbl_dcs_purchase_rate_applicability.purchase_rate_code  AND tbl_rate_download_ack.device_id='$device_id' AND tbl_rate_download_ack.hash_key='$hash_key' AND tbl_rate_download_ack.applicable_for!='MEMBER'")
                        ->where(['tbl_dcs_purchase_rate_applicability.purchase_rate_code' => $this->purchase_rate_code])
                        ->andWhere(['or',
                            ['tbl_dcs_purchase_rate_applicability.applicable_code' => $dcs_code, 'tbl_dcs_purchase_rate_applicability.applicable_for' => 'DCS'],
                            ['tbl_dcs_purchase_rate_applicability.applicable_code' => $plant_code, 'tbl_dcs_purchase_rate_applicability.applicable_for' => 'PLANT'],
                            ['tbl_dcs_purchase_rate_applicability.applicable_code' => $bmc_code, 'tbl_dcs_purchase_rate_applicability.applicable_for' => 'BMC'],
                            ['tbl_dcs_purchase_rate_applicability.applicable_code' => $mcc_plant_code, 'tbl_dcs_purchase_rate_applicability.applicable_for' => 'MCC'],
                            ['tbl_dcs_purchase_rate_applicability.applicable_code' => $customer_code, 'tbl_dcs_purchase_rate_applicability.applicable_for' => $customer_type]
                        ])
                        ->andWhere(['tbl_rate_download_ack.ack_id' => NULL])
                        ->all();
    }

}
