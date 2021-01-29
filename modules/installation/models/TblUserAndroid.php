<?php

namespace app\modules\installation\models;

use Yii;
use app\modules\details\models\TblContactDetails;
use app\modules\organisation\models\TblUnions;

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
    public $org_type, $org_code;
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
            [['user_code'], 'required'],
            [['created_at', 'updated_at', 'user_code', 'password', 'org_type', 'org_code', 'originating_org_code'], 'safe'],
            [['originating_type'], 'integer'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['name', 'username', 'mobile_no', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 128],
            [['device_id'], 'string', 'max' => 500],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'string', 'max' => 12],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
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
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
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

    public function getExistData($org_type, $data) {
        $query = $this->find()->where(['union_code' => $data->union_code]);
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

    public function getOrgType($data, $return = 'code') {
        if ($return == 'type') {
            if (!empty($data->dcs_code)) {
                return 'VLC';
            } elseif (!empty($data->bmc_code)) {
                return 'BMC';
            } elseif (!empty($data->mcc_plant_code)) {
                return 'MCC';
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

}
