<?php

namespace app\modules\vsp\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblRoutes;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * This is the model class for table "tbl_head_load_applicability".
 *
 * @property string $code
 * @property string $created_at
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $head_load_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblHeadLoad $headLoadCode
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblHeadLoadApplicability extends \app\models\ChildModel {

    public $route_code;
    public $organization;
    public $product_group_id;
    public $language_code;
    public $local_code;
    public $local_name;
    public $route;

    public static function tableName() {
        return 'tbl_head_load_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['applicable_code', 'wef_date', 'shift_code', 'shift_for'], 'required'],
                [['created_at', 'dcs_code', 'updated_at', 'organization', 'wef_date', 'route_code', 'union_code'], 'safe'],
                [['head_load_code'], 'string', 'max' => 35],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['head_load_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoad::className(), 'targetAttribute' => ['head_load_code' => 'head_load_code']],
                [['dcs_code'], 'ChangeDate'],
                [['applicable_code', 'applicable_for'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'route_code' => Yii::t('app', 'Route'),
            'created_by' => Yii::t('app', 'Created By'),
            'organization' => Yii::t('app', 'Organization'),
            'dcs_code' => Yii::t('app', 'Dcs'),
            'head_load_code' => Yii::t('app', 'Head Load'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'shift_for' => Yii::t('app', 'Applicable Shift'),
            'shift_code' => Yii::t('app', 'Wef Shift'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'name' => Yii::t('app', 'Applicable Name'),
        ];
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
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadLoadCode() {
        return $this->hasOne(TblHeadLoad::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getCodeold() {
        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(code) AS UNSIGNED)) as code")
                ->from('tbl_head_load_applicability')
                ->one();
        $number = (int) $val['code'] + 1;
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }

    public function getCode() {
        $len = strlen($this->head_load_code);
        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`code` FROM " . $len . " +1)) AS UNSIGNED)) as code")
                ->from('tbl_head_load_applicability')
                ->where(['head_load_code' => $this->head_load_code])
                ->one();
        $code = (int) $val['code'] + 1;
//        var_dump($this->purchase_rate_code.$code);exit;

        return $code;
    }

    /**
     * Return selected dcs/subcenter/route array in dcs head load mapping
     * @param type $headLoadCode
     * @param type $unionCode
     * @return type
     */
    public function getHeadLoadApplicability($headLoadCode, $unionCode) {

        $routes = new TblRoutes;
        $routes = $routes->getRoutes($unionCode);

        $selected = $this->find()->joinWith(['dcsCode'])->select('wef_date,tbl_head_load_applicability.dcs_code')->where(['head_load_code' => $headLoadCode])->all();
        $organizations = [];
        $selectedRoute = [];
        $selectedOrg = [];
        $orgFlag = 0;
        $wefDate = date('d-m-Y');
        foreach ($selected as $row) {
            if (!empty($row->dcs_code)) {
                $routeCode = $row->dcsCode->route_code;
                $orgFlag = 0;
                $selectedOrg[$row->dcs_code] = ['selected' => 'selected'];
            }
            $wefDate = $row->wef_date;
            $selectedRoute[$routeCode] = ['selected' => 'selected'];

            $returnArray = $this->getAllOrg($routeCode, $orgFlag);
            $organizations = array_merge($organizations, $returnArray);
        }
        return ['routes' => $routes, 'orgFlag' => $orgFlag, 'wefDate' => $wefDate, 'selectedRoutes' => $selectedRoute, 'selectedOrganization' => $selectedOrg, 'selectedAllOrg' => $organizations];
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

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getShiftCodeFor() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_for']);
    }

    public function ChangeDate() {
        $this->wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $this->wef_date .= ' ' . Yii::$app->general->getshift($this->shift_code);
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

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'applicable_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'applicable_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

}
