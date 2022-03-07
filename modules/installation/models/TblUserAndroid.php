<?php

namespace app\modules\installation\models;

use Yii;
use app\models\ChildModel;
use app\modules\details\models\TblContactDetails;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblMccPlant;
use app\modules\installation\models\TblUserRoleMapping;
use app\modules\installation\models\TblRole;
use app\modules\installation\models\TblUserDownloadAck;

/**
 * This is the model class for table "tbl_user_android".
 *
 * @property string $user_code
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $mobile_no
 * @property string $email
 * @property string $device_id
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
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
class TblUserAndroid extends \yii\db\ActiveRecord {

    public $toEncrypt = ['password'];
    public $org_type, $org_code, $repeat_password, $role_code;
    public $f_union_code, $f_plant_code, $f_mcc_code, $f_bmc_code, $f_dcs_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_android';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_code', 'username', 'name', 'password', 'mobile_no', 'repeat_password', 'plant_code', 'mcc_plant_code'], 'required', 'except' => ['installation', 'importCsv']],
                [['username', 'name', 'password', 'repeat_password', 'mobile_no', 'org_type', 'org_code'], 'required', 'on' => 'importCsv'],
                [['created_at', 'updated_at', 'user_code', 'password', 'org_type', 'org_code', 'originating_org_code', 'role_code', 'is_active', 'mobile_no', 'device_id'], 'safe'],
                [['originating_type'], 'integer'],
            //  [['username'], 'unique'],
            ['username', 'unique', 'targetAttribute' => ['username', 'union_code'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Username already Exists')],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['name', 'username', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
                [['email'], 'string', 'max' => 128],
                [['device_id'], 'string', 'max' => 500],
                [['union_code'], 'string', 'max' => 3],
                [['plant_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'string', 'max' => 12],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
                [['email'], 'email'],
                [['org_code'], 'setData', 'on' => 'importCsv'],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['repeat_password'], 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
                [['is_active'], 'default', 'value' => 1],
                [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
                [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code'], 'on' => ['importCsv']],
                [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'on' => ['importCsv']],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
                [['role_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblRole::className(), 'targetAttribute' => ['role_code' => 'role_code'], 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_code' => Yii::t('app', 'User Code'),
            'name' => Yii::t('app', 'Name'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email'),
            'device_id' => Yii::t('app', 'Device ID'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'is_active' => Yii::t('app', 'Is Active'),
            'dcs_code' => Yii::t('app', 'DCS'),
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

    public function getExistData($org_type, $data, $notIn = '') {
        $query = $this->find()->where(['union_code' => $data->union_code, 'is_active' => 1]);
        if (strtoupper($org_type == 'MCC')) {
            $query->andWhere(['plant_code' => $data->plant_code, 'mcc_plant_code' => $data->mcc_plant_code])->andWhere(['=', 'ISNULL(bmc_code,\'\')', '']);
        } elseif ($org_type == 'BMC') {
            $query->andWhere(['plant_code' => $data->plant_code, 'mcc_plant_code' => $data->mcc_plant_code, 'bmc_code' => $data->bmc_code])->andWhere(['=', 'ISNULL(dcs_code,\'\')', '']);
        } elseif ($org_type == 'VLC') {
            $query->andWhere(['plant_code' => $data->plant_code, 'mcc_plant_code' => $data->mcc_plant_code, 'bmc_code' => $data->bmc_code, 'dcs_code' => $data->dcs_code]);
        }
        if (!empty($notIn)) {
            $query->andWhere(['!=', 'user_code', $notIn]);
        }
        $user = $query->all();
        return $user;
    }

    public function getOrgType($data, $return = 'code') {
        if ($return == 'type') {
            if (!empty($data->dcs_code)) {
                return 'VLC';
            } elseif (!empty($data->bmc_code)) {
                return 'BMC';
            } elseif (!empty($data->mcc_plant_code)) {
                return 'MCC';
            }
        } elseif ($return == 'name') {
            if (!empty($data->dcs_code)) {
                return Yii::$app->general->getforeignkey($data->dcsCode, 'dcs_name');
            } elseif (!empty($data->bmc_code)) {
                return Yii::$app->general->getforeignkey($data->bmcCode, 'bmc_name');
            } elseif (!empty($data->mcc_plant_code)) {
                return Yii::$app->general->getforeignkey($data->mccCode, 'name');
            }
        } else {
            if (!empty($data->dcs_code)) {
                return $data->dcs_code;
            } elseif (!empty($data->bmc_code)) {
                return $data->bmc_code;
            } elseif (!empty($data->mcc_plant_code)) {
                return $data->mcc_plant_code;
            }
        }
    }

    public function getContactDetails($org_type, $data) {
        $contactModel = new TblContactDetails();
        $query = $contactModel->find()->where(['is_active' => 1, 'is_default' => 1]);
        if (strtoupper($org_type == 'MCC')) {
            $query->andWhere(['module_code' => $data->mcc_plant_code, 'module_name' => 'mccPlant']);
        } elseif ($org_type == 'BMC') {
            $query->andWhere(['module_code' => $data->bmc_code, 'module_name' => 'bmc']);
        } elseif ($org_type == 'VLC') {
            $query->andWhere(['module_code' => $data->dcs_code, 'module_name' => 'society']);
        }
        $user = $query->one();
        return $user;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            if ($this->hasAttribute('address'))
                \Yii::$app->general->validateDiscriptiveField($this, 'address');

            if ($this->hasAttribute('description'))
                \Yii::$app->general->validateDiscriptiveField($this, 'description');

            $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
            if ($insert) {
                if ($this->hasAttribute('created_by') && $this->created_by == NULL)
                    $this->created_by = $user;
                if ($this->hasAttribute('created_at') && $this->created_at == NULL)
                    $this->created_at = date('Y-m-d H:i:s');

                if ($this->hasAttribute('originating_org_code') && $this->originating_org_code == NULL) {
                    $this->originating_org_code = \Yii::$app->session->get('organizations_code');
                }
                if ($this->hasAttribute('originating_org_type') && $this->originating_org_type == NULL) {
                    $this->originating_org_type = 'PORTAL';
                }
                if ($this->hasAttribute('originating_type') && $this->originating_type == NULL) {
                    $this->originating_type = 0;
                }
                if ($this->hasAttribute('received_timestamp') && $this->received_timestamp == NULL) {
                    $this->received_timestamp = date('Y-m-d H:i:s');
                }
            } else {
                if ($this->hasAttribute('updated_by'))
                    $this->updated_by = $user;
                if ($this->hasAttribute('updated_at'))
                    $this->updated_at = date('Y-m-d H:i:s');
            }
            if ($this->hasAttribute('flg_sentbox_entry')) {
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    $this->flg_sentbox_entry = 'Y';
                }
            }
            if ($this->hasAttribute('sync_status')) {
                $this->sync_status = 'U';
            }

            $encrypt = $this->encryptModel($this->attributes);
            $this->setAttributes($encrypt);
            return true;
        } else {
            return false;
        }
    }

    public function afterFind() {
        $this->decryptModel($this);
        parent::afterFind();
    }

    public function decryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model->attributes));
        foreach ($result as $key => $value) {
            $decryptData = \Yii::$app->general->decryptData($model->{$value});
            if ($decryptData) {
                $model->{$value} = $decryptData;
            } else {
                if (($value == 'birth_date' || $value == 'dob') && !(bool) strtotime($model->{$value})) {
                    $model->{$value} = '';
                }
            }
        }
        return $model;
    }

    public function encryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model));
        foreach ($result as $key => $value) {
            if ($this->hasAttribute($value) && $this->{$value} != '')
                $model[$value] = \Yii::$app->general->encryptData($model[$value]);
        }
        return $model;
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getUserCode() {
        return $this->hasOne(TblUserRoleMapping::className(), ['user_code' => 'user_code']);
    }

    public function getMainExistData($org_type, $data, $username) {
        $query = $this->find()->where(['union_code' => $data->union_code, 'username' => $username]);
        if (strtoupper($org_type == 'MCC')) {
            $query->andWhere(['plant_code' => $data->plant_code, 'mcc_plant_code' => $data->mcc_plant_code]);
        } elseif ($org_type == 'BMC') {
            $query->andWhere(['plant_code' => $data->plant_code, 'mcc_plant_code' => $data->mcc_plant_code, 'bmc_code' => $data->bmc_code]);
        } elseif ($org_type == 'VLC') {
            $query->andWhere(['plant_code' => $data->plant_code, 'mcc_plant_code' => $data->mcc_plant_code, 'bmc_code' => $data->bmc_code, 'dcs_code' => $data->dcs_code]);
        }
        $user = $query->all();
        return $user;
    }

    public function setChildTable(&$model, &$modelSave, &$errors) {
        if (!empty($model)) {
            $model->union_code = Yii::$app->general->getforeignkey($model->mccCode, 'union_code');
            $ackModel = new TblUserDownloadAck();
            $ackModel->attributes = $model->attributes;
            $ackModel->download_pending = 1;
            $ackModel->union_code = $model->union_code;
            array_push($modelSave, $ackModel);

            if (!empty($model->role_code)) {
                $map = new TblUserRoleMapping();
                $map->role_code = $model->role_code;
                $map->user_code = $model->user_code;
                array_push($modelSave, $map);
            }
            if (!$ackModel->validate()) {
                $errors[] = $ackModel->getErrors();
            }
        }
    }

    public function setData() {
        if (!empty($this->org_type)) {
            if (strtoupper($this->org_type) == 'VLC') {
                $this->dcs_code = $this->org_code;
                $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');
                $this->plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'plant_code');
                $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'mcc_plant_code');
                $this->bmc_code = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
            } else if (strtoupper($this->org_type) == 'BMC') {
                $this->bmc_code = $this->org_code;
                $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
                $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            } else if (strtoupper($this->org_type) == 'MCC') {
                $this->mcc_plant_code = $this->org_code;
                $this->union_code = Yii::$app->general->getforeignkey($this->mccCode, 'union_code');
                $this->plant_code = Yii::$app->general->getforeignkey($this->mccCode, 'plant_code');
            } else {
                $this->addError('org_type', Yii::t('app/validation', 'Invalide Org Type'));
            }
        }
    }

}
