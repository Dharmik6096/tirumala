<?php

namespace app\modules\installation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_app_lock_password".
 *
 * @property string $app_lock_password_code
 * @property string $android_key
 * @property string $hour
 * @property string $app_password
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblAppLockPassword extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_app_lock_password';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['app_lock_password_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'android_key'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'originating_type', 'app_lock_password_code', 'hour', 'app_password'], 'safe'],
            [['android_key'], 'string', 'max' => 4, 'max' => 4],
            [['android_key'], 'integer'],
            [['dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['bmc_code'], 'validateHour'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'app_lock_password_code' => Yii::t('app', 'App Lock Password Code'),
            'android_key' => Yii::t('app', 'Android Key'),
            'hour' => Yii::t('app', 'Hour'),
            'app_password' => Yii::t('app', 'App Password'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function validateHour($attribute, $params) {
        $datetime = date('Y-m-d H:i:s', strtotime('-1 hour'));

        $Query = $this->find()
                ->where(['>', 'created_at', $datetime])
                ->andWhere(['bmc_code' => $this->bmc_code]);
        if (!empty($this->dcs_code)) {
            $Query->andWhere(['dcs_code' => $this->dcs_code]);
        } else {
            $Query->andWhere(["ISNULL(dcs_code, '')" => '']);
        }

        $count = $Query->count();

        if (empty($this->dcs_code)) {
            if ($count >= 4) {
                $this->addError('bmc_code', Yii::t('app/validation', 'You have exceeded the maximum number of attempts within 1 hour.'));
            }
        } else {
            if ($count > 0) {
                $this->addError('dcs_code', Yii::t('app/validation', 'You cannot generate Password within 1 hour.'));
            }
        }
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

}
