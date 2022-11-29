<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_device_config_temp_mapping".
 *
 * @property integer $config_temp_mapping_code
 * @property integer $device_temp_code
 * @property string $applicable_for
 * @property string $applicable_code
 * @property integer $is_active
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
class TblDeviceConfigTempMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_config_temp_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['device_temp_code', 'is_active', 'originating_type'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
            [['applicable_for', 'applicable_code'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['is_active'], 'default', 'value' => 1],
            [['applicable_code'], 'CheckDuplicate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'config_temp_mapping_code' => Yii::t('app', 'Config Temp Mapping Code'),
            'device_temp_code' => Yii::t('app', 'Device Temp Code'),
            'applicable_for' => Yii::t('app', 'Applicable Type'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'is_active' => Yii::t('app', 'Is Active'),
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

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getmainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function CheckDuplicate($attribute, $param) {
        if (is_array($this->applicable_code)) {
            foreach ($this->applicable_code as $applicablecode) {
                $data = $this->find()
                                ->where(['applicable_code' => $applicablecode, 'applicable_for' => $this->applicable_for, 'device_temp_code' => $this->device_temp_code])
                                ->andWhere(['is_active' => 1])->one();
                if (!empty($data)) {
                    $this->addError($attribute, Yii::t('app/validation', '{attribute} has already been taken.'));
                }
            }
        }
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'applicable_code']);
    }

}
