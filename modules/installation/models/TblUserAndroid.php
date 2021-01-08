<?php

namespace app\modules\installation\models;

use Yii;

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
class TblUserAndroid extends \app\models\ChildModel {

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
            [['created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'integer'],
            [['user_code', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['name', 'username', 'mobile_no', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 100],
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
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
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
        $query = $this->find()->where(['union_code' => $data->union_code, 'device_id' => $data->device_id]);
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

}
