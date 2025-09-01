<?php

namespace app\models;

use Yii;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblFederations;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblRouteMapping;

/**
 * This is the model class for table "tbl_user_organization_mapping".
 *
 * @property string $id
 * @property string $created_at
 * @property integer $is_active
 * @property string $organization_code
 * @property string $organization_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $user_id
 *
 * @property TblUsers $user
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 */
class TblUserOrganizationMapping extends ChildModel {

    public $organization;
    public $federation;
    public $union;
    public $plant;
    public $bmc;
    public $mcc;
    public $dcs;
    public $route;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_organization_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['federation'], 'required', 'on' => 'organizationMapping'],
                [['union'], 'required', 'on' => 'organizationMappingUnion'],
                [['federation', 'union', 'dcs', 'created_at', 'deleted_at', 'updated_at', 'organization', 'plant', 'mcc', 'bmc'], 'safe'],
                [['is_active'], 'integer'],
                [['organization_code', 'organization_type'], 'string', 'max' => 25],
                [['created_by', 'updated_by', 'user_id'], 'string', 'max' => 14],
                [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
                [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
                [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
                [['federation'], 'validateOnLoginType'],
        ];
    }

    public function validateChecked($attribute, $params) {

        if (!empty($this->identity)) {
            $ary = explode('-', $this->identity);
            if (empty($this->$attribute) && $ary[2] == '4') {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' cannot be blank.'));
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'organization' => Yii::t('app', 'Organization'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'user_id' => Yii::t('app', 'User ID'),
            'federation' => Yii::t('app', 'FEDERATION'),
            'union' => Yii::t('app', 'UNION'),
            'plant' => Yii::t('app', 'PLANT'),
            'mcc' => Yii::t('app', 'MCC'),
            'bmc' => Yii::t('app', 'BMC'),
            'dcs' => Yii::t('app', 'DCS'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(TblUsers::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(TblUsers::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(TblUsers::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return TblUserOrganizationMappingQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblUserOrganizationMappingQuery(get_called_class());
    }

    public function getOrganization($id, $orgType) {
        $userOrg = User::getSelectedOrganization($orgType);

        foreach ($userOrg['data'] as $row) {
            $data[$row->{$userOrg['field'][0]}] = $row->{$userOrg['field'][1]};
        }
        $values = $this->find()->select('organization_code,organization_type')->where(['user_id' => $id, 'is_active' => 1])->asArray()->all();
        $selected = [];
        foreach ($data as $key => $row) {
            if (array_search($key, array_column($values, 'organization_code')) !== FALSE) {
                $selected[$key] = ['selected' => 'selected'];
            }
        }
        return ['value' => $data, 'selected' => $selected];
    }

    public function getOrganizationsArray($id, $orgType, $orgArray = []) {
        if (!empty($orgArray) && empty($orgType)) {
            $orgType = !empty($orgArray[0]['organization_type_id']) ? $orgArray[0]['organization_type_id'] : '';
        }
        $userOrg = User::getSelectedOrganization($orgType);
        $fedModel = new TblFederations();
        $federations = $fedModel->getActiveFederation();
        $uniModel = new TblUnions();
        $unions = $uniModel->getActiveUnions(1);
        $plant = ['data' => [], 'selectedArray' => []];
        $mcc = ['data' => [], 'selectedArray' => []];
        $bmc = ['data' => [], 'selectedArray' => []];
        $dcs = ['data' => [], 'selectedArray' => []];
        $route = ['data' => [], 'selectedArray' => []];
        $federations = ['data' => $federations, 'selectedArray' => []];
        $unions = ['data' => $unions, 'selectedArray' => []];
        $data = [];
        foreach ($userOrg['data'] as $row) {
            $data_key = $row[$userOrg['field'][0]];
            $data_val = $row[$userOrg['field'][1]];
            $data[$data_key] = $data_val;
        }
        $values = $this->find()->select('organization_code,organization_type')->where(['user_id' => $id, 'is_active' => 1])->asArray()->all();
        if (empty($values) && !empty($orgArray)) {
            $values = $orgArray;
        }
        $selected = [];
        foreach ($data as $key => $row) {
            if (array_search($key, array_column($values, 'organization_code')) !== FALSE) {
                $selected[$key] = (string) $key;
            }
        }

        switch ($orgType) {
            case '7' :
                if (!empty($orgArray) && !empty($orgType) && !empty($orgArray['bmc'])) {
                    foreach($orgArray['bmc'] as $key => $value){
                        $selected[$value] = $value;
                    }
                }
                $federations = $this->getFederations();
                $unions = $this->getUnions($federations['selectedArray'], 0);
                $bmc_temp = $this->getBMC(0, $selected);
                $mcc_temp = $this->getMCC(0, $bmc_temp['selectedArray']);
                $plant_temp = $this->getPlant(0, $mcc_temp['selectedArray']);
                $uni_temp = $this->getUnions(0, $plant_temp['selectedArray']);
                $unions['selectedArray'] = $uni_temp['selectedArray'];
                $plant = $this->getPlant($unions['selectedArray'], 0);
                $plant['selectedArray'] = $plant_temp['selectedArray'];
                $mcc = $this->getMCC($plant['selectedArray'], 0);
                $mcc['selectedArray'] = $mcc_temp['selectedArray'];
                $bmc = $this->getBMC($mcc['selectedArray'], 0);
                $bmc['selectedArray'] = $bmc_temp['selectedArray'];
                $dcs = $this->getDcs($bmc['selectedArray']);
                $dcs['selectedArray'] = $selected;
                $route = $this->getRoute($plant['selectedArray'], $mcc['selectedArray'], $bmc['selectedArray'], 0);
                $route_temp = $this->getRoute($plant['selectedArray'], $mcc['selectedArray'], $bmc['selectedArray'], $dcs['selectedArray']);
                $route['selectedArray'] = $route_temp['selectedArray'];
                break;
            case '6' :
                if (!empty($orgArray) && !empty($orgType) && !empty($orgArray['mcc'])) {
                    foreach($orgArray['mcc'] as $key => $value){
                        $selected[$value] = $value;
                    }
                }
                $federations = $this->getFederations();
                $unions = $this->getUnions($federations['selectedArray'], 0);
                $mcc_temp = $this->getMCC(0, $selected);
                $plant_temp = $this->getPlant(0, $mcc_temp['selectedArray']);
                $uni_temp = $this->getUnions(0, $plant_temp['selectedArray']);
                $unions['selectedArray'] = $uni_temp['selectedArray'];
                $plant = $this->getPlant($unions['selectedArray'], 0);
                $plant['selectedArray'] = $plant_temp['selectedArray'];
                $mcc = $this->getMCC($plant['selectedArray'], 0);
                $mcc['selectedArray'] = $mcc_temp['selectedArray'];
                $bmc = $this->getBMC($mcc['selectedArray'], 0);
                $bmc['selectedArray'] = $selected;
                $dcs = $this->getDcs($selected);
                $route = $this->getRoute($plant['selectedArray'], $mcc['selectedArray'], $bmc['selectedArray'], 0);
                break;
            case '5' :
                if (!empty($orgArray) && !empty($orgType) && !empty($orgArray['plant'])) {
                    foreach($orgArray['plant'] as $key => $value){
                        $selected[$value] = $value;
                    }
                }
                $federations = $this->getFederations();
                $unions = $this->getUnions($federations['selectedArray'], 0);
                $plant = $this->getPlant(0, $selected);
                $uni_temp = $this->getUnions(0, $plant['selectedArray']);
                $unions['selectedArray'] = $uni_temp['selectedArray'];
                $plant = $this->getPlant($unions['selectedArray'], 0);
                $plant_temp = $this->getPlant(0, $selected);
                $plant['selectedArray'] = $plant_temp['selectedArray'];
                $mcc = $this->getMCC($plant['selectedArray'], 0);
                $mcc['selectedArray'] = $selected;
                $bmc = $this->getBMC($selected, 0);
                $dcs = $this->getDcs($bmc['selectedArray']);
                $route = $this->getRoute($plant['selectedArray'], $mcc['selectedArray'], 0, 0);
                break;
            case '4':
                $federations = $this->getFederations();
                $unions = $this->getUnions($federations['selectedArray'], 0);
                $uni_temp = $this->getUnions($federations['selectedArray'], $selected);
                $unions['selectedArray'] = $uni_temp['selectedArray'];
                $plant = $this->getPlant($unions['selectedArray'], 0);
                $plant['selectedArray'] = $selected;
                $mcc = $this->getMCC($selected, 0);
                $bmc = $this->getBMC($mcc['selectedArray'], 0);
                $dcs = $this->getDcs($bmc['selectedArray']);
                $route = $this->getRoute($plant['selectedArray'], 0, 0, 0);
                break;
            case '3':
                $federations = $this->getFederations();
                $unions = $this->getUnions($federations['selectedArray'], 0);
                $unions['selectedArray'] = $selected;
                $plant = $this->getPlant($selected, 0);
                $mcc = $this->getMCC($plant['selectedArray'], 0);
                $bmc = $this->getBMC($mcc['selectedArray'], 0);
                $dcs = $this->getDcs($bmc['selectedArray']);
                $route = $this->getRoute(0, 0, 0, 0);
                break;
            case '2':
                $federations = ['data' => $data, 'selectedArray' => $selected];
                $unions = $this->getUnions($selected, 0);
                $plant = $this->getPlant($unions['selectedArray'], 0);
                $mcc = $this->getMCC($plant['selectedArray'], 0);
                $bmc = $this->getBMC($mcc['selectedArray'], 0);
                $dcs = $this->getDcs($bmc['selectedArray']);
                $route = $this->getRoute(0, 0, 0, 0);
                break;
        }
        return ['federation' => $federations, 'union' => $unions, 'plant' => $plant, 'mcc' => $mcc, 'bmc' => $bmc, 'dcs' => $dcs, 'route' => $route];
    }

    private function getDcs($BMCArray) {
        $query = TblDcs::find();
        $query->select(['dcs_code', 'dcs_name']);
        $query->where(['is_active' => 1]);
        if (!empty(Yii::$app->session->get('Unions'))) {
            $sel = explode(',', Yii::$app->session->get('Unions'));
            $query->andWhere(['union_code' => $sel]);
        }
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $sel = explode(',', Yii::$app->session->get('Dcs'));
            $query->andWhere(['dcs_code' => $sel]);
        }
        if ($BMCArray !== 0) {
            $query->andWhere(['bmc_code' => $BMCArray]);
        }
        $list = $query->asArray()->all();
        $data = ArrayHelper::map($list, 'dcs_code', 'dcs_name');
        return ['data' => $data, 'selectedArray' => []];
    }

    private function getBMC($MCCArray, $DcsArray) {
        $query = TblDcsBmc::find();
        $query->select(['bmc_code', 'bmc_name']);
        $query->where(['is_active' => 1]);
        if (!empty(Yii::$app->session->get('Unions'))) {
            $sel = explode(',', Yii::$app->session->get('Unions'));
            $query->andWhere(['union_code' => $sel]);
        }
        if (!empty(Yii::$app->session->get('BMC'))) {
            $sel = explode(',', Yii::$app->session->get('BMC'));
            $query->andWhere(['bmc_code' => $sel]);
        }
        $selected = [];
        if ($MCCArray !== 0) {
            $query->andWhere(['mcc_plant_code' => array_keys($MCCArray)]);
        }
        $list = $query->asArray()->all();
        $data = ArrayHelper::map($list, 'bmc_code', 'bmc_name');
        if ($DcsArray != 0) {
            $codes = TblDcs::find()->select(['bmc_code'])->where(['dcs_code' => array_values($DcsArray)])->asArray()->all();
            $query->andWhere(['bmc_code' => $codes]);
            $list = $query->asArray()->all();

            $data = ArrayHelper::map($list, 'bmc_code', 'bmc_name');
            foreach ($data as $key => $row) {
                $selected[$key] = $key;
            }
        }
        return ['data' => $data, 'selectedArray' => $selected];
    }

    private function getMCC($plantArray, $BMCArray) {
        $query = TblMccPlant::find();
        $query->select(['mcc_plant_code', 'name']);
        $query->where(['is_active' => 1]);
        if (!empty(Yii::$app->session->get('Unions'))) {
            $sel = explode(',', Yii::$app->session->get('Unions'));
            $query->andWhere(['union_code' => $sel]);
        }
        if (!empty(Yii::$app->session->get('MCC'))) {
            $sel = explode(',', Yii::$app->session->get('MCC'));
            $query->andWhere(['mcc_plant_code' => $sel]);
        }
        $selected = [];
        if ($plantArray !== 0) {
            $query->andWhere(['plant_code' => array_keys($plantArray)]);
        }
        $list = $query->asArray()->all();
        $data = ArrayHelper::map($list, 'mcc_plant_code', 'name');
        if ($BMCArray != 0) {
            $codes = TblDcsBmc::find()->select(['mcc_plant_code As mcc_plant_code'])->where(['bmc_code' => array_flip($BMCArray)])->asArray()->all();
            $query->andWhere(['mcc_plant_code' => $codes]);
            $list = $query->asArray()->all();
            $data = ArrayHelper::map($list, 'mcc_plant_code', 'name');
            foreach ($data as $key => $row) {
                $selected[$key] = $key;
            }
        }
        return ['data' => $data, 'selectedArray' => $selected];
    }

    private function getPlant($unionArray, $MCCArray) {
        $query = TblPlant::find();
        $query->select(['plant_code', 'name']);
        $query->where(['is_active' => 1]);
        if (!empty(Yii::$app->session->get('Unions'))) {
            $sel = explode(',', Yii::$app->session->get('Unions'));
            $query->andWhere(['union_code' => $sel]);
        }
        if (!empty(Yii::$app->session->get('Plant'))) {
            $sel = explode(',', Yii::$app->session->get('Plant'));
            $query->andWhere(['plant_code' => $sel]);
        }
        $selected = [];
        if ($unionArray !== 0) {
            $query->andWhere(['union_code' => array_keys($unionArray)]);
        }
        $list = $query->asArray()->all();
        $data = ArrayHelper::map($list, 'plant_code', 'name');
        if ($MCCArray != 0) {
            $codes = TblMccPlant::find()->select(['plant_code'])->where(['mcc_plant_code' => array_flip($MCCArray)])->asArray()->all();
            $query->andWhere(['plant_code' => $codes]);
            $list = $query->asArray()->all();
            $data = ArrayHelper::map($list, 'plant_code', 'name');
            foreach ($data as $key => $row) {
                $selected[$key] = $key;
            }
        }

        return ['data' => $data, 'selectedArray' => $selected];
    }

    private function getUnions($fedearionArray, $plantArray) {
        $query = TblUnions::find();
        $query->select(['union_code', 'union_name']);
        $query->where(['is_active' => 1]);
        $selected = [];
        if (!empty(Yii::$app->session->get('Unions'))) {
            $sel = explode(',', Yii::$app->session->get('Unions'));
            $query->andWhere(['union_code' => $sel]);
        }
        if ($fedearionArray !== 0) {
            $query->andWhere(['federation_code' => array_keys($fedearionArray)]);
        }
        $list = $query->asArray()->all();
        $data = ArrayHelper::map($list, 'union_code', 'union_name');
        if ($plantArray != 0) {
            $codes = TblPlant::find()->select(['union_code'])->where(['plant_code' => array_flip($plantArray)])->asArray()->all();
            $query->andWhere(['union_code' => $codes]);
            $list = $query->asArray()->all();
            $data = ArrayHelper::map($list, 'union_code', 'union_name');
            foreach ($data as $key => $row) {
                $selected[$key] = $key;
            }
        }

        return ['data' => $data, 'selectedArray' => $selected];
    }

    private function getFederations() {
        $allFeder = User::getSelectedOrganization(2);
        $selected = [];
        foreach ($allFeder['data'] as $row) {
            $data[$row->{$allFeder['field'][0]}] = $row->{$allFeder['field'][1]};
        }
        foreach ($data as $key => $row) {
            $selected[$key] = $key;
        }
        return ['data' => $data, 'selectedArray' => $selected];
    }

    public function getCode() {

        $orgCode = Yii::$app->session->get('organizations_code');
        $len = strlen($orgCode);

        $val = (new \yii\db\Query)
                ->select(["MAX(convert(int,id)) as id"])
                ->from('tbl_user_organization_mapping')
                ->one();
        $code1 = (int) $val['id'] + 1;

        $value = $orgCode . $code1;

        return $value;
    }

    public function getInstalltionCode($orgCode) {

        $orgCode = Yii::$app->session->get('organizations_code');
        $len = strlen($orgCode);

        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`id` FROM " . $len . " +1)) AS UNSIGNED)) as id")
                ->from('tbl_user_organization_mapping')
                ->where('(CAST(trim(SUBSTRING(id, 1,' . $len . ')) AS UNSIGNED))="' . trim($orgCode) . '"')
                ->one();
        $code1 = (int) $val['id'] + 1;

        $value = $orgCode . $code1;

        return $value;
    }

    public function getUserOrgs($userCode) {
        $records = $this->find()->select(['organization_code', 'organization_type'])->where(['user_id' => $userCode, 'is_active' => 1])->asArray()->all();
        return $records;
    }

    public function getUserMapping() {
        return $this->find()->where(['user_id' => $this->user_id])->all();
    }

    public function getUserOrgMapping() {
        return $this->find()->where(['user_id' => $this->user_id, 'organization_type' => $this->organization_type])->all();
    }

    public function getAllUserOrgMapping() {
        return $this->find()->where(['user_id' => $this->user_id, 'organization_type' => $this->organization_type, 'organization_code' => $this->organization_code, 'is_active' => 1])->all();
    }

    private function getRoute($PlantArray, $MCCArray, $BMCArray, $DcsArray) {
        $query = TblRouteMapping::find();
        $query->select(['route_code', 'route_name']);
        $query->where(['to_dest' => $PlantArray, 'to_type' => 'plant']);

        $selected = [];
        if ($MCCArray !== 0) {
            $query->orWhere(['to_dest' => array_keys($MCCArray), 'to_type' => 'mcc']);
        }
        if ($BMCArray !== 0) {
            $query->orWhere(['to_dest' => array_keys($BMCArray), 'to_type' => 'bmc']);
        }
        $list = $query->asArray()->all();
        $data = ArrayHelper::map($list, 'route_code', 'route_name');
        if ($DcsArray != 0) {
            $codes = TblDcs::find()->select(['route_code'])->where(['dcs_code' => array_values($DcsArray)])->asArray()->all();
            $query->andWhere(['route_code' => $codes]);
            $list = $query->asArray()->all();

            $data = ArrayHelper::map($list, 'route_code', 'route_name');
            foreach ($data as $key => $row) {
                $selected[$key] = $key;
            }
        }
        return ['data' => $data, 'selectedArray' => $selected];
    }

    public function getUserMaster() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function validateOnLoginType($attribute, $params) {
        if (!empty($this->user_id)) {
            $login_type = Yii::$app->general->getforeignkey($this->userMaster, 'login_type');
            if (!empty($login_type)) {
                if ($login_type == 'vsp' || $login_type == 'DCS') {
                    if ((empty($this->dcs)) || count($this->dcs) != 1) {
                        $msg = empty($this->dcs) ? Yii::t('app', 'DCS') . ' cannot be blank.' : 'Allow to Map single ' . Yii::t('app', 'DCS');
                        $this->addError('dcs', Yii::t('app/validation', $msg));
                        return false;
                    }
                } else if ($login_type == 'MCC') {
                    if ((empty($this->mcc))) {
                        $msg = Yii::t('app', 'MCC') . ' cannot be blank.';
                        $this->addError('mcc', Yii::t('app/validation', $msg));
                        return false;
                    } elseif (!empty($this->dcs)) {
                        $mccArray = [];
                        foreach ($this->dcs as $dcs) {
                            $data = explode(':', $dcs);
                            $key = !empty($data[1]) ? $data[1] : $data[0];
                            if (!in_array($key, $mccArray)) {
                                $mccArray[] = $key;
                            }
                        }
                        if (count($mccArray) > 1) {
                            $msg = 'Allow to select ' . Yii::t('app', 'DCS') . ' of any single ' . Yii::t('app', 'MCC');
                            $this->addError('dcs', Yii::t('app/validation', $msg));
                            return false;
                        }
                    }
                } else if ($login_type == 'route_supervisor') {
                    if ((empty($this->mcc))) {
                        $msg = Yii::t('app', 'MCC') . ' cannot be blank.';
                        $this->addError('mcc', Yii::t('app/validation', $msg));
                        return false;
                    } else if ((empty($this->dcs))) {
                        $msg = Yii::t('app', 'DCS') . ' cannot be blank.';
                        $this->addError('dcs', Yii::t('app/validation', $msg));
                        return false;
                    }
                } else if ($login_type == 'UNION') {
                    if ((empty($this->union))) {
                        $msg = Yii::t('app', 'UNION') . ' cannot be blank.';
                        $this->addError('union', Yii::t('app/validation', $msg));
                        return false;
                    }
                } else if ($login_type == 'PLANT') {
                    if ((empty($this->plant))) {
                        $msg = Yii::t('app', 'PLANT') . ' cannot be blank.';
                        $this->addError('plant', Yii::t('app/validation', $msg));
                        return false;
                    }
                } else if ($login_type == 'BMC') {
                    if ((empty($this->bmc))) {
                        $msg = Yii::t('app', 'BMC') . ' cannot be blank.';
                        $this->addError('bmc', Yii::t('app/validation', $msg));
                        return false;
                    }
                } else if ($login_type == 'ROUTE') {
                    if ((empty($this->route))) {
                        $msg = Yii::t('app', 'ROUTE') . ' cannot be blank.';
                        $this->addError('route', Yii::t('app/validation', $msg));
                        return false;
                    }
                }
            }
        }
    }

}
