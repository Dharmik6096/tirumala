<?php

namespace app\modules\tms\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\usermanagement\models\User;
use app\modules\tms\models\TblTaskType;
use app\modules\tms\models\TblFormType;

/**
 * This is the model class for table "tbl_task".
 *
 * @property integer $task_code
 * @property integer $task_type_code
 * @property integer $form_type_code
 * @property string $task_performed_for
 * @property string $title
 * @property string $description
 * @property string $task_datetime
 * @property integer $is_cancel
 * @property string $user_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property integer $is_notified
 * @property string $notified_datetime
 * @property string $pick_datetime
 * @property string $response_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblTask extends \app\models\ChildModel {

    public $dcs_code, $route_code, $repeat_interval, $week_days, $start_date, $end_date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_task';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['status'], 'default', 'value' => 'OPEN'],
                [['is_cancel', 'is_notified', 'resp_status'], 'default', 'value' => 0],
                [['task_performed_for', 'task_type_code', 'plant_code', 'title', 'description', 'user_code', 'start_date', 'repeat_interval'], 'required', 'on' => ['addTask']],
                [['task_type_code', 'form_type_code', 'is_cancel', 'is_notified', 'originating_type', 'reference_type', 'reference_code'], 'safe'],
                [['task_datetime', 'notified_datetime', 'pick_datetime', 'response_datetime', 'created_at', 'updated_at', 'user_code'], 'safe'],
                [['task_performed_for'], 'string', 'max' => 10],
                [['title'], 'string', 'max' => 100],
                [['description'], 'string', 'max' => 255],
                [['user_code', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['bmc_code'], 'string', 'max' => 12],
                [['mcc_plant_code', 'plant_code'], 'string', 'max' => 6],
                [['union_code'], 'string', 'max' => 3],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['dcs_code', 'route_code', 'repeat_interval', 'week_days', 'start_date', 'end_date'], 'safe'],
                [['mcc_plant_code', 'bmc_code'], 'required', 'when' => function ($model) {
                    return in_array($model->task_performed_for, ['BMC', 'DCS']);
                }, 'whenClient' => "function (attribute, value) { 
                   return jQuery.inArray($('#tbltask-task_performed_for').val(), ['BMC','DCS']) != -1
                   }", 'on' => ['addTask']],
                [['dcs_code', 'route_code'], 'required', 'when' => function ($model) {
                    return in_array($model->task_performed_for, ['DCS']);
                }, 'whenClient' => "function (attribute, value) { 
                   return jQuery.inArray($('#tbltask-task_performed_for').val(), ['DCS']) != -1
                   }", 'on' => ['addTask']],
                [['form_type_code'], 'required', 'when' => function ($model) {
                    return FALSE;
                }, 'whenClient' => "function (attribute, value) { 
                   return $('select#tbltask-form_type_code option').length > 1 
                   }", 'on' => ['addTask']],
                [['end_date'], 'required', 'when' => function ($model) {
                    return in_array($model->repeat_interval, ['1', '2']);
                }, 'whenClient' => "function (attribute, value) { 
                   return jQuery.inArray($('#tbltask-repeat_interval').val(), ['1', '2']) != -1 
                   }", 'on' => ['addTask']],
                [['week_days'], 'required', 'when' => function ($model) {
                    return in_array($model->repeat_interval, ['2']);
                }, 'whenClient' => "function (attribute, value) { 
                   return jQuery.inArray($('#tbltask-repeat_interval').val(), ['2']) != -1 
                   }", 'on' => ['addTask']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'task_code' => Yii::t('app', 'Task Code'),
            'task_type_code' => Yii::t('app', 'Task Type'),
            'form_type_code' => Yii::t('app', 'Form Type'),
            'task_performed_for' => Yii::t('app', 'Location Type'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'task_datetime' => Yii::t('app', 'Task Date'),
            'is_cancel' => Yii::t('app', 'Is Cancel ?'),
            'user_code' => Yii::t('app', 'User'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'route_code' => Yii::t('app', 'ROUTE'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'PLANT'),
            'union_code' => Yii::t('app', 'UNION'),
            'is_notified' => Yii::t('app', 'Is Notified ?'),
            'notified_datetime' => Yii::t('app', 'Notified Datetime'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'end_date' => Yii::t('app', 'End Date *'),
            'week_days' => Yii::t('app', 'Week Days *'),
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

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'user_code']);
    }

    public function getTaskTypeCode() {
        return $this->hasOne(TblTaskType::className(), ['task_type_code' => 'task_type_code']);
    }

    public function getFormTypeCode() {
        return $this->hasOne(TblFormType::className(), ['form_type_code' => 'form_type_code']);
    }

    public function getPickRecords($limit = 100) {
        return $query = $this->find()
                        ->where(['or', ['resp_status' => NULL], ['resp_status' => ''], ['resp_status' => 0]])
                        ->andWhere(['<=', 'task_datetime', date('Y-m-d H:i:s')])
                        ->limit($limit)
                        ->orderBy([
                            'task_datetime' => SORT_ASC,
                        ])->all();
    }

    public function updatePickStatus($ids) {
        return $this->updateAll(['resp_status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['task_code' => $ids]);
    }

    public function updateProcessStatus() {
        return $this->updateAll(['resp_status' => $this->resp_status, 'is_notified' => $this->is_notified, 'notified_datetime' => $this->notified_datetime, 'response_datetime' => date('Y-m-d H:i:s')], ['task_code' => $this->task_code]);
    }

}
