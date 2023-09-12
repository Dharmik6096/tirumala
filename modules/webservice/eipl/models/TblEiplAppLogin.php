<?php

namespace app\modules\webservice\eipl\models;

use Yii;
use app\modules\details\models\TblContactDetails;
use app\modules\dcsoperation\models\TblMember;
use yii\db\Expression;
use app\modules\general\models\TblDepartment;

/**
 * This is the model class for table "tbl_eipl_app_login".
 *
 * @property integer $app_login_id
 * @property integer $app_type
 * @property string $eipl_code
 * @property string $mobile_no
 * @property string $master_type
 * @property string $master_code
 * @property string $login_type
 * @property string $module_type
 * @property string $module_code
 * @property integer $otp_code
 * @property string $imei_no
 * @property string $device_id
 * @property string $lat_long
 * @property string $access_token
 * @property string $auth_key
 * @property string $version_no
 * @property string $orignating_timestamp
 * @property string $posting_timestamp
 * @property integer $sms_sent
 * @property string $sms_log
 * @property integer $is_active
 * @property integer $is_expired
 * @property string $expired_datetime
 * @property string $updated_at
 */
class TblEiplAppLogin extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_login';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['app_type', 'otp_code', 'sms_sent', 'is_active', 'is_expired', 'device_detail'], 'safe'],
            [['eipl_code', 'mobile_no', 'master_type', 'master_code', 'login_type', 'module_type', 'module_code', 'imei_no', 'device_id', 'lat_long', 'access_token', 'auth_key', 'version_no', 'sms_log'], 'safe'],
            [['orignating_timestamp', 'posting_timestamp', 'expired_datetime', 'updated_at', 'department'], 'safe'],
            [['orignating_timestamp', 'posting_timestamp', 'expired_datetime', 'updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'app_login_id' => Yii::t('app', 'App Login ID'),
            'app_type' => Yii::t('app', 'App Type'),
            'eipl_code' => Yii::t('app', 'Eipl Code'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'master_type' => Yii::t('app', 'Master Type'),
            'master_code' => Yii::t('app', 'Master Code'),
            'login_type' => Yii::t('app', 'Login Type'),
            'module_type' => Yii::t('app', 'Module Type'),
            'module_code' => Yii::t('app', 'Module Code'),
            'otp_code' => Yii::t('app', 'Otp Code'),
            'imei_no' => Yii::t('app', 'Imei No'),
            'device_id' => Yii::t('app', 'Device ID'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'access_token' => Yii::t('app', 'Access Token'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'version_no' => Yii::t('app', 'Version No'),
            'orignating_timestamp' => Yii::t('app', 'Orignating Timestamp'),
            'posting_timestamp' => Yii::t('app', 'Posting Timestamp'),
            'sms_sent' => Yii::t('app', 'Sms Sent'),
            'sms_log' => Yii::t('app', 'Sms Log'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_expired' => Yii::t('app', 'Is Expired'),
            'expired_datetime' => Yii::t('app', 'Expired Datetime'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblEiplAppLoginQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblEiplAppLoginQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['app_login_id' => $id]);
    }

    /**
      /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->app_login_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->password === $password;
    }

    public function beforeSave($insert) {
        $this->updated_at = date('Y-m-d H:i:s');
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
            }
            return true;
        }
        return false;
    }

    public function getLogin() {
        $encryptedmobile = Yii::$app->general->encryptData($this->mobile_no);
        $query = $this->find()->where(['or',
                    ['mobile_no' => $encryptedmobile],
                    ['mobile_no' => $this->mobile_no]
                ])
                ->andWhere(['app_type' => $this->app_type]);
        if ($this->login_type == 'MEMBER') {
            $query = $query->andWhere(['login_type' => $this->login_type, 'module_code' => $this->module_code]);
        } else {
            $query = $query->andWhere(['!=', 'login_type', 'MEMBER']);
        }
        return $query->one();
    }

    public function activationInfo() {
        $encryptedmobile = Yii::$app->general->encryptData($this->mobile_no);
        return $this->find()->where(['app_type' => $this->app_type, 'otp_code' => $this->otp_code,
                                //'device_id' => $this->device_id
                        ])
                        ->andWhere(['or',
                            ['mobile_no' => $encryptedmobile],
                            ['mobile_no' => $this->mobile_no]
                        ])
                        ->one();
    }

    public function orgMobileDetail() {
        $encryptedmobile = Yii::$app->general->encryptData($this->mobile_no);
        $userType = ['bmc',
            'mccPlant',
            'plant',
            'routeMapping',
            'society',
            'union', 'user'];
        $module_name = $userType;
        return $OrgContacts = TblContactDetails::find()
                        ->select(['master_type' => 'module_name',
                            'master_code' => 'module_code',
                            'module_type' => new Expression("'TblContactDetails'"),
                            'module_code' => 'CAST(detail_code as varchar)',
                            'login_type' => "UPPER(CASE WHEN (select TOP 1(organization_type) from tbl_app_organization_mapping where mobile_no=[tbl_contact_details].mobile_no) is not null THEN"
                            . "(select TOP 1(organization_type) from tbl_app_organization_mapping where mobile_no=[tbl_contact_details].mobile_no) "
                            . "WHEN (module_name='mccPlant') THEN 'MCC' "
                            . "WHEN  (module_name='routeMapping') THEN 'ROUTE' "
                            . "WHEN  (module_name='society') THEN 'DCS' "
                            . "ELSE module_name END)",
                            'department' => 'department',
                            'module_name' => 'firstname',
                        ])
                        ->where(['or',
                            ['mobile_no' => $encryptedmobile],
                            ['mobile_no' => $this->mobile_no]
                        ])->andWhere(['module_name' => $module_name, 'is_active' => 1]);
    }

    public function getLoginOrg() {
        return $this->hasMany(TblAppOrganizationMapping::className(), ['mobile_no' => 'mobile_no'])->andwhere(['is_active' => 1]);
    }

    public function memberMobileDetail() {
        $encryptedmobile = Yii::$app->general->encryptData($this->mobile_no);
        return $contactDetail = TblMember::find()
                        ->select(['master_type' => new Expression("'member'"),
                            'master_code' => 'member_code',
                            'module_type' => new Expression("'TblMember'"),
                            'module_code' => 'member_code',
                            'login_type' => new Expression("'MEMBER'"),
                            'department' => new Expression("'MEMBER'"),
                            'module_name' => 'member_name',
                        ])
                        ->where(['or',
                            ['mobile_no' => $encryptedmobile],
                            ['mobile_no' => $this->mobile_no]
                        ])->andWhere(['is_active' => 1])->andFilterWhere(['member_code' => $this->module_code]);
    }

    public function MobileNoDetail() {
        $orgData = $this->orgMobileDetail();
        $contactDetails = $this->memberMobileDetail();
        if ($orgData->count() == '1') {
            $contactDetails = $contactDetails->union($orgData);
        }
        return $contactDetails->asArray()->all();
    }

    public function getMasterDetail() {
        if ($this->login_type == 'MEMBER') {
            return $this->hasOne(TblMember::className(), ['member_code' => 'module_code']);
        } else {
            return $this->hasOne(TblContactDetails::className(), ['detail_code' => 'module_code']);
        }
    }

    public function getDepartmentCode() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department']);
    }

    public function getLoginData($data) {
        $query = $this->find()->alias('ap')
                ->select(['ap.device_id', 'ap.master_code'])
                ->join('INNER JOIN', 'tbl_member m', 'm.member_code = ap. master_code')
                ->join('INNER JOIN', 'tbl_dcs d', 'd.dcs_code = m. dcs_code')
                ->where(['d.dcs_code' => $data->dcs_code, 'd.union_code' => $data->union_code, 'd.plant_code' => $data->plant_code, 'd.mcc_plant_code' => $data->mcc_plant_code, 'd.bmc_code' => $data->bmc_code])
                ->andWhere(['ap.is_active' => 1, 'ap.login_type' => $data->login_type, 'ISNULL(ap.is_block,0)' => 0]);

        if (!empty($data->member_code)) {
            $query = $query->andWhere(['m.member_code' => $data->member_code]);
        }
        return $query->all();
    }

    public function getMemberAppInfo($data) {
        $encryptedmobile = Yii::$app->general->encryptData($data->mobile_no);
        return $query = $this->find()
                        ->where(['or',
                            ['mobile_no' => $encryptedmobile],
                            ['mobile_no' => $data->mobile_no]
                        ])->andWhere(['master_code' => $data->member_code, 'login_type' => 'MEMBER'])->one();
    }

    public function getAppLogin($user) {
        $encryptedmobile = Yii::$app->general->encryptData($this->mobile_no);
        return $query = $this->find()
                        ->where(['or',
                            ['mobile_no' => $encryptedmobile],
                            ['mobile_no' => $this->mobile_no]
                        ])->andWhere(['master_code' => $user])->one();
    }

    public function getAppDetail($data) {
        $encryptedmobile = Yii::$app->general->encryptData($data->mobile_no);
        return $query = $this->find()
                        ->where(['or',
                            ['mobile_no' => $encryptedmobile],
                            ['mobile_no' => $data->mobile_no]
                        ])->andWhere(['master_code' => $data->id, 'login_type' => $data->login_type])->one();
    }

}
