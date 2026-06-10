<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblManufacturer;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_bmc_silos_info".
 *
 * @property integer $bmc_silos_info_code
 * @property string $silo_no
 * @property string $description
 * @property integer $manufacturer_code
 * @property string $model
 * @property string $wef_date
 * @property integer $storage_capacity
 * @property integer $chilling_capacity
 * @property string $owning_type
 * @property integer $milk_type_code
 * @property string $module_name
 * @property string $module_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblBmcSilosInfo extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_silos_info';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['silo_no', 'description', 'model', 'owning_type', 'module_name', 'module_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['manufacturer_code', 'storage_capacity', 'chilling_capacity', 'milk_type_code', 'originating_type'], 'integer'],
            [['wef_date', 'created_at', 'updated_at', 'is_active', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
            [['silo_no', 'storage_capacity', 'chilling_capacity', 'owning_type'], 'required'],
            ['silo_no', 'unique', 'targetAttribute' => ['silo_no', 'module_code', 'module_name'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'silo_no' => Yii::t('app', 'Silo No'),
            'description' => Yii::t('app', 'Description'),
            'manufacturer_code' => Yii::t('app', 'Manufacturer'),
            'model' => Yii::t('app', 'Model'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'storage_capacity' => Yii::t('app', 'Storage Capacity'),
            'chilling_capacity' => Yii::t('app', 'Chilling Capacity'),
            'owning_type' => Yii::t('app', 'Owning Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function setModel($module, $module_code) {
        $this->module_name = $module;
        $this->module_code = $module_code;
        $this->is_active = 1;
        if ($this->module_name == 'BMC') {
            $this->bmc_code = $this->module_code;
            $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        } else {
            $this->mcc_plant_code = $this->module_code;
            $this->union_code = Yii::$app->general->getforeignkey($this->mccCode, 'union_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        }
    }

    public function getManufacturerCode() {
        return $this->hasOne(TblManufacturer::className(), ['id' => 'manufacturer_code']);
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'module_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $bmc_code = $mcc_code = $plant_code = '';
        if (strtoupper($this->module_name) == 'BMC') {
            $bmc_code = $this->bmc_code;
        }
        $mcc_code = $this->mcc_plant_code;
        $plant_code = $this->plant_code;
        $sentboxArray = Yii::$app->general->getSentBoxCodes($plant_code, $mcc_code, $bmc_code, '', '', false);
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
        if (!empty($this->bmc_code)) {
            $masterCacheKey = 'bmc_dispatch_master_union_' . $this->union_code . '_bmc_' . $this->bmc_code;
            Yii::$app->general->removeRedisCache($masterCacheKey);
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'module_code']);
    }

}
