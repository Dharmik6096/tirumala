<?php

namespace app\modules\veterinary\models;

use app\models\ChildModel;
use app\models\TblUserOrganizationMapping;
use Yii;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_medicine_stock".
 *
 * @property integer $medicine_stock_id
 * @property integer $medicine_id
 * @property string $union_code
 * @property string $module_name
 * @property string $module_code
 * @property string $stock
 * @property string $batch_no
 * @property string $expire_date
 * @property string $rate
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMedicineStock extends ChildModel {

    public $user_code, $organization_code;
    public $mcc_plant_code, $bmc_code, $dcs_code, $plant_code;
    public $from_user_code, $medicine_wise, $to_user_code;
    private $stockUpdated = false;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_medicine_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_id', 'union_code', 'module_name', 'module_code', 'stock', 'batch_no', 'expire_date', 'rate', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'user_code', 'organization_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'plant_code'], 'safe'],
            [['medicine_id', 'user_code', 'stock', 'batch_no', 'expire_date', 'rate'], 'required', 'on' => ['importCsv']],
            [['user_code'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_code' => 'id']],
            [['expire_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['expire_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['expire_date'], 'convertDate', 'on' => ['importCsv']],
            [['module_name'], 'default', 'value' => 'USER'],
            [['user_code'], 'setImport', 'on' => ['importCsv']],
            [['stock', 'rate'], 'number'],
            [['union_code'], 'string', 'max' => 5],
            [['module_name', 'module_code'], 'string', 'max' => 50],
            [['batch_no'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'medicine_stock_id' => Yii::t('app', 'Medicine Stock ID'),
            'medicine_id' => Yii::t('app', 'Medicine ID'),
            'union_code' => Yii::t('app', 'Union'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'stock' => Yii::t('app', 'Stock'),
            'batch_no' => Yii::t('app', 'Batch No'),
            'expire_date' => Yii::t('app', 'Expire Date'),
            'rate' => Yii::t('app', 'Rate'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
        ];
    }

    public function convertDateDot() {
        try {
            $this->expire_date = Yii::$app->controls->view_date($this->expire_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->expire_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->expire_date = !empty($this->expire_date) ? Yii::$app->controls->view_date($this->expire_date, 'php:Y-m-d') : NULL;
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->module_code = $this->user_code;
            $userOrganizationMappingData = TblUserOrganizationMapping::find()->select(['organization_type', 'organization_code'])->where(['user_id' => $this->user_code])->orderBy(['created_at' => SORT_DESC])->one();

            if (empty($userOrganizationMappingData)) {
                $this->addError('user_code', 'User is not mapped to any organization.');
                return false;
            } else {
                $this->organization_code = $userOrganizationMappingData->organization_code;
            }

            switch ($userOrganizationMappingData->organization_type) {
                case 'UNION':
                    $this->union_code = $this->organization_code;
                    break;
                case 'BMC':
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    break;
                case 'PLANT':
                    $this->union_code = Yii::$app->general->getforeignkey($this->plantCode, 'union_code');
                    break;
                case 'DCS':
                    $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');
                    break;
                case 'MCC':
                    $this->union_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'union_code');
                    break;
                default:
                    $this->addError('user_code', 'User is not mapped to any ' . Yii::t('app', 'Union') . '.');
                    return false;
                    break;
            }

            if (!empty($this->union_code)) {
                if (is_numeric($this->medicine_id)) {
                    $existingMedicine = TblMedicineMaster::find()->select('medicine_id')->where(['union_code' => $this->union_code, 'medicine_id' => $this->medicine_id])->scalar();
                } else {
                    $existingMedicine = TblMedicineMaster::find()->select('medicine_id')->where(['union_code' => $this->union_code, 'medicine_name' => $this->medicine_id])->scalar();
                }
                if (empty($existingMedicine)) {
                    $this->addError($attribute, 'Medicine with ID/Name \'' . $this->medicine_id . '\' does not exist.');
                    return false;
                } else {
                    $this->medicine_id = $existingMedicine;
                }
            }
            return true;
        }
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'organization_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'organization_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'organization_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'organization_code']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'module_code']);
    }

    public function getMedicineMasterCode() {
        return $this->hasOne(TblMedicineMaster::className(), ['medicine_id' => 'medicine_id']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function setChildTable(&$model, &$saveModel, &$errors) {
        if (!empty($model)) {
            $medicineStockData = $this->find()->where(['union_code' => $this->union_code, 'medicine_id' => $this->medicine_id, 'module_name' => $this->module_name, 'module_code' => $this->module_code, 'batch_no' => $this->batch_no])->one();
            $oldValue = 0;
            $newValue = $model->stock;
            if (!empty($medicineStockData)) {
                $historyModel = new TblMedicineStockHistory();
                Yii::$app->operation->history($medicineStockData, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
                $oldValue = $medicineStockData->stock;
                $stock = $medicineStockData->stock + $model->stock;
                $medicineStockData->stock = $stock;
                $model = $medicineStockData;
            }

            $medicineStockTxn = new TblMedicineStockTransaction();
            $medicineStockTxn->attributes = $model->attributes;
            $medicineStockTxn->tran_datetime = date('Y-m-d H:i:s');
            $medicineStockTxn->old_value = $oldValue;
            $medicineStockTxn->new_value = $newValue;
            $medicineStockTxn->final_value = $oldValue + $newValue;
            $medicineStockTxn->entry_type = 'OPENING';
            unset($medicineStockTxn->created_at);
            unset($medicineStockTxn->created_by);
            unset($medicineStockTxn->updated_at);
            unset($medicineStockTxn->updated_by);
            unset($medicineStockTxn->originating_org_code);
            unset($medicineStockTxn->originating_org_type);
            unset($medicineStockTxn->originating_type);
            $saveModel[] = $medicineStockTxn;
        }
    }

    public function getUserList($type, $code) {
        $userData = User::find()
                ->alias('u')
                ->innerJoin('tbl_user_organization_mapping', 'tbl_user_organization_mapping.user_id = u.user_code')
                ->innerJoin('tbl_medicine_stock', 'tbl_medicine_stock.module_code = u.user_code')
                ->where(['>', 'tbl_medicine_stock.stock', 0])
                ->andWhere(['in', 'tbl_user_organization_mapping.organization_type', $type])
                ->andWhere(['tbl_user_organization_mapping.organization_code' => $code])
                ->andWhere(['u.is_active' => 1])
                ->select(['u.id', 'u.mobile_no', 'u.name'])
                ->distinct()
                ->all();

        $user = ArrayHelper::map($userData, 'id', function ($data) {
                    $extras = array_filter([$data['mobile_no'] ?? null]);
                    return $data['name'] . (!empty($extras) ? ' (' . implode(' - ', $extras) . ')' : '');
                });

        return $user;
    }

    public function getAllUserList($code) {
        $userData = User::find()
                ->alias('u')
                ->joinWith(['unionCode', 'dcsCode', 'mccPlantCode', 'bmcCode', 'plantCode'])
                ->innerJoin('tbl_user_organization_mappings', 'tbl_user_organization_mapping.user_id = u.user_code')
                ->select(['u.id', 'u.mobile_no', 'u.name'])
                ->distinct()
                ->all();

        $user = ArrayHelper::map($userData, 'id', function ($data) {
                    $extras = array_filter([$data['mobile_no'] ?? null]);
                    return $data['name'] . (!empty($extras) ? ' (' . implode(' - ', $extras) . ')' : '');
                });

        return $user;
    }

}
