<?php

namespace app\modules\tms\models;

use Yii;

/**
 * This is the model class for table "tbl_user_attendance_history".
 *
 * @property integer $id
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
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $duration
 * @property integer $api_status
 * @property string $pick_datetime
 * @property string $response_datetime
 * @property string $response_msg
 */
class TblUserAttendanceHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_attendance_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['attendance_code'], 'required'],
            [['attendance_code', 'originating_type', 'api_status'], 'integer'],
            [['attendance_date', 'in_time', 'out_time', 'created_at', 'updated_at', 'history_created_at', 'duration', 'pick_datetime', 'response_datetime'], 'safe'],
            [['day_count'], 'number'],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code'], 'string', 'max' => 6],
            [['mcc_plant_code', 'bmc_code'], 'string', 'max' => 12],
            [['dcs_code', 'status', 'created_by', 'updated_by'], 'string', 'max' => 20],
            [['user_code'], 'string', 'max' => 25],
            [['in_lat_long', 'out_lat_long', 'in_desc', 'out_desc', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'response_msg'], 'string', 'max' => 255],
            [['history_created_by'], 'string', 'max' => 14],
            [['operation_type'], 'string', 'max' => 10],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'attendance_code' => Yii::t('app', 'Attendance Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'user_code' => Yii::t('app', 'User Code'),
            'attendance_date' => Yii::t('app', 'Attendance Date'),
            'in_time' => Yii::t('app', 'In Time'),
            'out_time' => Yii::t('app', 'Out Time'),
            'day_count' => Yii::t('app', 'Day Count'),
            'in_lat_long' => Yii::t('app', 'In Lat Long'),
            'out_lat_long' => Yii::t('app', 'Out Lat Long'),
            'in_desc' => Yii::t('app', 'In Desc'),
            'out_desc' => Yii::t('app', 'Out Desc'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'duration' => Yii::t('app', 'Duration'),
            'api_status' => Yii::t('app', 'Api Status'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'response_msg' => Yii::t('app', 'Response Msg'),
        ];
    }

}
