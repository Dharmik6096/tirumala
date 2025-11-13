<?php

namespace app\modules\organisation\models;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\syncutility\models\TblSentbox;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSocietyCodesHistory;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\organisation\models\TblCustomerMasterHistory;
use yii\base\UserException;

/**
 * This is the model class for table "tbl_route_mapping_sources".
 *
 * @property integer $route_mapping_source_code
 * @property string $route_code
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
 * @property TblRouteMapping $routeCode
 */
class TblRouteMappingSources extends \app\models\ChildModel {

    public $dcs_code, $dcs_name, $dcs_code_ex, $ref_code;
    public $is_sentbox;
    public $customer_code, $customer_type, $union_code, $user_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_route_mapping_sources';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['customer_type'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->routeCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importMapping']],
            [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'on' => ['importCsv']],
            [['route_code', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'customer_type', 'customer_code', 'union_code'], 'safe'],
            [['is_active'], 'default', 'value' => 1],
            [['is_active'], 'integer'],
            [['route_code'], 'validRoute', 'on' => ['importMapping']],
            [['route_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblRouteMapping::className(), 'targetAttribute' => ['route_code' => 'route_code']],
            [['route_code'], 'importData', 'on' => ['importMapping']],
            [['route_code', 'customer_type', 'customer_code'], 'required', 'on' => ['importMapping']],
            [['route_code'], 'unique', 'targetAttribute' => ['route_code', 'from_type', 'from_dest', 'to_type', 'to_dest'], 'message' => Yii::t('app/validation', 'Record is Already Exist.'), 'skipOnError' => true, 'on' => ['importMapping']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'route_mapping_source_code' => Yii::t('app', 'Route Mapping Source Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    /**
     * @inheritdoc
     * @return TblRouteMappingSourcesQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblRouteMappingSourcesQuery(get_called_class());
    }

    public function getDestinations($code, $route_type, $union_code, $route_dest_type = 'from') {

        $results = new TblRouteMapping();
        $results = $results->getDestinations($route_type, $union_code, $route_dest_type = 'from', $code);
        $values = $this->find()->select('from_dest')->where(['route_code' => $code->route_code, 'is_active' => 1])->asArray()->all();
        $selected = [];
        //var_dump($results);exit;
        if (!empty($results)) {
            foreach ($results as $key => $row) {
                if (in_array($row['code'], array_column($values, 'from_dest'), true) !== FALSE) {
                    $selected[] = $row['code'] . '-' . $row['tname'];
                }
            }
        }
        //var_dump($selected);exit;
        return ['destinations' => $results, 'selected' => $selected];
    }

    public function getDcsCode() {
        return $this->hasMany(TblDcs::className(), ['dcs_code' => 'from_dest']);
    }

    public function getRoutesWithDcs() {
        $query = TblSocietyCodes::find()->select(['route_code', 'dcs_code']);
        if (!empty(Yii::$app->session->get('Unions'))) {
            $query->where(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        $routes = $query->all();
        $dcs_routes = \yii\helpers\ArrayHelper::getColumn($routes, 'route_code');

        return empty($dcs_routes) ? [] : $dcs_routes;
    }

    public function getSocietyCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'from_dest']);
    }

    public function getRouteDcsData() {
        return $this->find()
                        ->where(['from_dest' => $this->from_dest, 'from_type' => 'society'])
                        ->one();
    }

    public function getRouteDCS($id, $values) {
        $query = $this->find()
                ->select(['tbl_dcs.dcs_code', 'tbl_dcs.dcs_name', 'tbl_dcs.dcs_code_ex', 'tbl_dcs.ref_code'])
                ->where(['tbl_route_mapping_sources.route_code' => $id, 'tbl_route_mapping_sources.is_active' => 1])
                ->andWhere(['not in', 'tbl_route_mapping_sources.from_dest', $values])
                ->joinWith(['dcsCode']);

        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andWhere(['tbl_dcs.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        return $query->all();
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        if ($this->to_type == 'bmc') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->to_dest);
        } else if ($this->to_type == 'mcc') {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->to_dest, '');
        }
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = Yii::$app->general->getforeignkey($this->societyCode, 'union_code');
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

    public function importData($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->union_code = Yii::$app->general->getforeignkey($this->routeCode, 'union_code');
            $customerType = Yii::$app->general->getforeignkey($this->customerType, 'customer_type');
            if (empty($customerType)) {
                $this->addError('customer_type', Yii::t('app/validation', Yii::t('app', 'Customer Type') . ' is invalid'));
            } else {
                $this->validateCustomer($this);
            }
            $this->to_dest = Yii::$app->general->getforeignkey($this->routeCode, 'to_dest');
            $this->to_type = Yii::$app->general->getforeignkey($this->routeCode, 'to_type');
            $this->from_dest = $this->customer_code;
            $this->from_type = strtoupper($this->customer_type) == 'DCS' ? 'society' : $this->customer_type;
            //validate dcs in To Dest
            if ($this->from_type == 'society') {
                if (strtolower($this->to_type) == 'plant') {
                    $plant = Yii::$app->general->getforeignkey($this->societyCode, 'plant_code');
                    if (!empty($plant) && $plant != $this->to_dest) {
                        $this->addError('from_dest', Yii::t('app/validation', Yii::t('app', 'Customer Code') . ' is invalid'));
                    }
                } elseif (strtolower($this->to_type) == 'mcc') {
                    $mcc = Yii::$app->general->getforeignkey($this->societyCode, 'mcc_plant_code');
                    if (!empty($mcc) && $mcc != $this->to_dest) {
                        $this->addError('from_dest', Yii::t('app/validation', Yii::t('app', 'Customer Code') . ' is invalid'));
                    }
                } elseif (strtolower($this->to_type) == 'bmc') {
                    $bmc = Yii::$app->general->getforeignkey($this->societyCode, 'bmc_code');
                    if (!empty($bmc) && $bmc != $this->to_dest) {
                        $this->addError('from_dest', Yii::t('app/validation', Yii::t('app', 'Customer Code') . ' is invalid'));
                    }
                }
            } else {
                //validate Route Should of BMC of customer
                if (strtolower($this->to_type) == 'plant') {
                    $plant = Yii::$app->general->getforeignkey($this->customerMainCode, 'plant_code');
                    if (!empty($plant) && $plant != $this->to_dest) {
                        $this->addError('from_dest', Yii::t('app/validation', Yii::t('app', 'Route Code') . ' is invalid For given Customer Code'));
                    }
                } elseif (strtolower($this->to_type) == 'mcc') {
                    $mcc = Yii::$app->general->getforeignkey($this->customerMainCode, 'mcc_plant_code');
                    if (!empty($mcc) && $mcc != $this->to_dest) {
                        $this->addError('from_dest', Yii::t('app/validation', Yii::t('app', 'Route Code') . ' is invalid For given Customer Code'));
                    }
                } elseif (strtolower($this->to_type) == 'bmc') {
                    $bmc = Yii::$app->general->getforeignkey($this->customerMainCode, 'bmc_code');
                    if (!empty($bmc) && $bmc != $this->to_dest) {
                        $this->addError('from_dest', Yii::t('app/validation', Yii::t('app', 'Route Code') . ' is invalid For given Customer Code'));
                    }
                }
            }
        }
    }

    public function setChildTableSaveDelete(&$model, &$modelSave, &$deleteModel, $unlink_files, $attachments, $masterdoc, $errors) {
        $modelRouteSource = TblRouteMapping::find()->where(['route_code' => $model->route_code])->one();
        if (strtolower($this->from_type) == 'society') {
            $oldRoutes = TblRouteMappingSources::find()->where(['from_dest' => $model->from_dest, 'from_type' => 'society'])->all();
            if (!empty($oldRoutes)) {
                foreach ($oldRoutes as $oldRoute) {
                    $sourcesHistory = new TblRouteMappingSourcesHistory();
                    Yii::$app->operation->history($oldRoute, $sourcesHistory, DELETE);
                    array_push($modelSave, $sourcesHistory);
                    array_push($deleteModel, $oldRoute);
                }
            }
            $societyCodes = TblSocietyCodes::find()->where(['dcs_code' => $model->from_dest])->one();
            if (!empty($societyCodes)) {
                $historyModel = new TblSocietyCodesHistory();
                Yii::$app->operation->history($societyCodes, $historyModel, UPDATE);
                $societyCodes->route_code = $modelRouteSource->route_code;
//                $societyCodes->pooling_point_code = str_pad((int) $societyCodes->getPpCode() + 1, 3, '0', STR_PAD_LEFT);
                array_push($modelSave, $societyCodes);
                array_push($modelSave, $historyModel);
            }
            $dcsCode = TblDcs::findOne($model->from_dest);
            if (!empty($dcsCode)) {
                $dcsHistoryModel = new TblDcsHistory();
                Yii::$app->operation->history($dcsCode, $dcsHistoryModel, UPDATE);
                $dcsCode->route_code = $model->route_code;
                $dcsCode->scenario = 'routeMapping';
                array_push($modelSave, $dcsCode);
                array_push($modelSave, $dcsHistoryModel);
            }
        } else {
            $model = TblCustomerMaster::find()->where(['customer_code' => $model->from_dest, 'customer_type' => $model->from_type])->one();
            $custoHistoryModel = new TblCustomerMasterHistory();
            Yii::$app->operation->history($model, $custoHistoryModel, UPDATE);
            $model->route_code = $modelRouteSource->route_code;
            array_push($modelSave, $custoHistoryModel);
        }
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code'])->andOnCondition(['is_active' => 1, 'is_routemapping' => 1]);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->dcs_code_ex]);
    }

    public function validateCustomer($model) {
        if (empty($model->customer_type) || strtoupper($model->customer_type) == 'DCS') {
            $model->customer_type = 'DCS';
            $dcs = new TblDcs();
            $model->customer_code = $dcs->getValidDcs($model->customer_code);
        } else {
            $model->customer_type = strtoupper($model->customer_type);
            $model->customer_code = $this->validateCustomerCode($model);
        }
        if (empty($model->customer_code)) {
            $model->addError('customer_code', Yii::t('app/validation', Yii::t('app', 'Customer Code') . ' is invalid'));
        }
    }

    public function validateCustomerCode($model) {
        if (strtolower($model->customer_type) != 'dcs') {
            $prefix = Yii::$app->general->getforeignkey($model->customerType, 'code_prefix');
            $length = Yii::$app->general->getforeignkey($model->customerType, 'code_length');
            $Code = '';
            if (!empty($prefix) && is_numeric($this->customer_code)) {
                $customerModel = new TblCustomerMaster();
                $customerModel->customer_type = $this->customer_type;
                $customerModelData = $customerModel->find()
                        ->where(['customer_type' => $this->customer_type])
                        ->andWhere(['CAST(REPLACE(customer_code_ex,\'' . $prefix . '\', \'\') as int)' => (int) $this->customer_code])
                        ->all();
                if (count($customerModelData) == 1) {
                    $Code = $customerModelData[0]->customer_code;
                    $this->ex_code = $customerModelData[0]->customer_code_ex;
                }
            } else {
                $model->dcs_code_ex = $prefix . str_pad($model->customer_code, $length, '0', STR_PAD_LEFT);
                $Code = Yii::$app->general->getforeignkey($model->customerCode, 'customer_code');
            }
            return $data = empty($Code) ? '' : $Code;
        }
    }

    public function getCustomerMainCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function validRoute() {
        $Route = new TblRouteMapping();
        $data = $Route->find()->select('route_code')->where(['or', ['route_code' => $this->route_code], ['route_code_ex' => $this->route_code], ['ref_code' => $this->route_code]])->andWhere(['is_active' => 1])->all();
        if (!empty($data) && count($data) == 1) {
            $this->route_code = $data[0]->route_code;
        } else {
            $this->addError('route_code', Yii::t('app/validation', Yii::t('app', 'Route Code') . ' is invalid'));
        }
    }

}
