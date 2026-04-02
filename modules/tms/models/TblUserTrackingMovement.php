<?php

namespace app\modules\tms\models;

use Yii;
use app\models\ChildModel;
use app\models\TblUserOrganizationMapping;
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
            [['tracking_datetime', 'created_at', 'updated_at', 'union_code', 'plant_code', 'mcc_plant_code', 'department'], 'safe'],
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
            'department' => Yii::t('app', 'Department'),
        ];
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'user_code']);
    }

    public function getUserList($code) {
        $query1 = TblUserOrganizationMapping::find()
                ->alias('tuom')
                ->select(['tcd.mobile_no', 'tuom.user_id AS user_id', 'u.login_type', 'u.name AS user_name', 'u.employee_id', new \yii\db\Expression("'1' AS order_by")])
                ->innerJoin('user u', 'tuom.user_id = u.id')
                ->innerJoin('tbl_contact_details tcd', 'tcd.module_code = u.id')
                ->where(['tuom.organization_code' => $code, 'tuom.organization_type' => 'MCC']);

        $query2 = User::find()
                ->alias('u')
                ->select(['tcd.mobile_no', 'u.id AS user_id', new \yii\db\Expression("u.login_type AS login_type"), 'u.name AS user_name', 'u.employee_id', new \yii\db\Expression("'2' AS order_by")])
                ->distinct()
                ->innerJoin('tbl_contact_details tcd', 'tcd.module_code = u.id')
                ->innerJoin('tbl_user_organization_mapping tuom', "tuom.user_id = u.id AND tuom.organization_type = 'DCS'")
                ->innerJoin('tbl_dcs d', 'd.dcs_code = tuom.organization_code')
                ->where(['d.mcc_plant_code' => $code]);
        
        $query3 = User::find()
                ->alias('u')
                ->select(['tcd.mobile_no', 'u.id AS user_id', new \yii\db\Expression("u.login_type AS login_type"), 'u.name AS user_name', 'u.employee_id', new \yii\db\Expression("'2' AS order_by")])
                ->distinct()
                ->innerJoin('tbl_contact_details tcd', 'tcd.module_code = u.id')
                ->innerJoin('tbl_user_organization_mapping tuom', "tuom.user_id = u.id AND tuom.organization_type = 'BMC'")
                ->innerJoin('tbl_bmc b', 'b.bmc_code = tuom.organization_code')
                ->where(['b.mcc_plant_code' => $code]);

        $unionSql = (new \yii\db\Query())
                ->select('*')
                ->from(['unioned' => $query1->union($query2)->union($query3)])
                ->orderBy(['order_by' => SORT_ASC]);

        $userData = $unionSql->all();

        $user = ArrayHelper::map($userData, 'user_id', function ($data) {
                    $extras = array_filter([$data['login_type'] ?? null, $data['mobile_no'] ?? null]);
                    return $data['user_name'] . (!empty($extras) ? ' (' . implode(' - ', $extras) . ')' : '') . (!empty($data['employee_id']) ? ' - ' . $data['employee_id'] : '');
                });

        return $user;
    }

}