<?php

namespace app\modules\installation\models;

use Yii;
use app\modules\details\models\TblContactDetails;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_android_installation".
 *
 * @property string $android_installation_id
 * @property string $organization_code
 * @property string $organization_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAndroidInstallation extends \app\models\ChildModel {

    public $union_code, $plant_code, $mcc_plant_code, $bmc_code, $dcs_code, $db_version;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_android_installation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['android_installation_id'], 'required'],
            [['android_installation_id', 'organization_code', 'organization_type', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'db_version'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'db_version'], 'required', 'on' => ['create_portal']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'android_installation_id' => Yii::t('app', 'Android Installation ID'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'db_version' => Yii::t('app', 'Version'),
        ];
    }

    public function getCode() {
        return \Faker\Provider\Uuid::uuid();
    }

    public function getData() {
        return $this->find()
                        ->where(['organization_code' => $this->organization_code, 'organization_type' => $this->organization_type])
                        ->one();
    }

    public function getDefaultContactDetail() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'organization_code'])->where(['tbl_contact_details.module_name' => 'society', 'tbl_contact_details.is_default' => 1]);
    }

    public function getInstallDetail() {
        return $this->hasOne(TblAndroidInstallationDetails::className(), ['android_installation_id' => 'android_installation_id']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'organization_code']);
    }

}
