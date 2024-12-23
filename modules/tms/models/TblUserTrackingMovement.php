<?php

namespace app\modules\tms\models;

use Yii;
use app\models\ChildModel;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_user_tracking_movement".
 *
 * @property integer $tracking_id
 * @property string $tracking_datetime
 * @property string $lat_long
 * @property string $module_name
 * @property string $module_code
 * @property string $user_code
 * @property string $mobile_no
 * @property string $login_type
 * @property string $device_id
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
class TblUserTrackingMovement extends ChildModel {

    public $union_code, $plant_code, $mcc_plant_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_tracking_movement';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tracking_datetime', 'created_at', 'updated_at', 'union_code', 'plant_code', 'mcc_plant_code'], 'safe'],
            [['originating_type'], 'integer'],
            [['lat_long', 'module_name', 'mobile_no', 'login_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['module_code'], 'string', 'max' => 50],
            [['user_code', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['device_id'], 'string', 'max' => 500],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tracking_id' => Yii::t('app', 'Tracking ID'),
            'tracking_datetime' => Yii::t('app', 'Tracking Datetime'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'user_code' => Yii::t('app', 'User'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'login_type' => Yii::t('app', 'Login Type'),
            'device_id' => Yii::t('app', 'Device ID'),
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
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
        ];
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'user_code']);
    }

    public function getUserList($code) {
        $userData = $this->find()
                ->select(['u.id', 'u.name', 'u.mobile_no', 'u.login_type'])
                ->distinct()
                ->from('tbl_mcc_plant mcc')
                ->innerJoin('tbl_user_organization_mapping om', 'om.organization_code = mcc.mcc_plant_code')
                ->innerJoin('user u', 'u.user_code = om.user_id')
                ->where(['om.organization_type' => 'MCC', 'mcc.mcc_plant_code' => $code])
                ->asArray()
                ->all();

        $user = ArrayHelper::map($userData, 'id', function ($data) {
                    $extras = array_filter([$data['login_type'] ?: null, $data['mobile_no'] ?: null]);
                    return $data['name'] . (!empty($extras) ? ' (' . implode(' - ', $extras) . ')' : '');
                });

        return $user;
    }

}