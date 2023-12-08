<?php

namespace app\modules\tms\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\usermanagement\models\User;
use app\modules\general\models\TblAttachment;
/**
 * This is the model class for table "tbl_user_attendance".
 *
 * @property integer $attendance_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $user_code
 * @property string $attendance_date
 * @property string $in_time
 * @property string $out_time
 * @property string $day_count
 * @property string $in_lat_long
 * @property string $out_lat_long
 * @property string $in_desc
 * @property string $out_desc
 * @property string $status
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblUserAttendance extends \app\models\ChildModel
{
    public $mobile_no, $total_hours;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attendance_date', 'in_time', 'out_time', 'created_at', 'updated_at'], 'safe'],
            [['day_count'], 'number'],
            [['originating_type'], 'integer'],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code'], 'string', 'max' => 6],
            [['mcc_plant_code', 'bmc_code'], 'string', 'max' => 12],
            [['dcs_code', 'status', 'created_by', 'updated_by'], 'string', 'max' => 20],
            [['user_code', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['in_lat_long', 'out_lat_long', 'in_desc', 'out_desc', 'remarks'], 'string', 'max' => 255],
            [['attendance_date', 'user_code'], 'unique', 'targetAttribute' => ['attendance_date', 'user_code'], 'message' => 'The combination of User Code and Attendance Date has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attendance_code' => Yii::t('app', 'Attendance Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'user_code' => Yii::t('app', 'User Name'),
            'attendance_date' => Yii::t('app', 'Attendance Date'),
            'in_time' => Yii::t('app', 'In Time'),
            'out_time' => Yii::t('app', 'Out Time'),
            'day_count' => Yii::t('app', 'Day Count'),
            'in_lat_long' => Yii::t('app', 'In Lat Long'),
            'out_lat_long' => Yii::t('app', 'Out Lat Long'),
            'in_desc' => Yii::t('app', 'Check In Address'),
            'out_desc' => Yii::t('app', 'Check Out Address'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::class, ['union_code' => 'union_code']);
    }
    
    public function getPlantCode() {
        return $this->hasOne(TblPlant::class, ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::class, ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::class, ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::class, ['dcs_code' => 'dcs_code']);
    }

    public function getUserCode() {
        return $this->hasOne(User::class, ['id' => 'user_code']);
    }

    public function getAttachment() {
        return $this->hasOne(TblAttachment::class, ['module_code' => 'attendance_code']);
    }

    public function getUserAttendance() {
        return $this->find()->where(['attendance_code' => $this->attendance_code])->one();
    }

}
