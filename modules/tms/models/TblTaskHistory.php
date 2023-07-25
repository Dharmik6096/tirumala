<?php

namespace app\modules\tms\models;

use Yii;

/**
 * This is the model class for table "tbl_task_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
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
 * @property string $status
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
class TblTaskHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_task_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'task_datetime', 'notified_datetime', 'pick_datetime', 'response_datetime', 'created_at', 'updated_at'], 'safe'],
                [['task_code', 'task_type_code', 'form_type_code', 'is_cancel', 'is_notified', 'originating_type'], 'safe'],
                [['operation_type', 'task_performed_for', 'status'], 'safe'],
                [['history_created_by', 'created_by', 'updated_by'], 'safe'],
                [['title'], 'safe'],
                [['description'], 'safe'],
                [['user_code', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['bmc_code'], 'safe'],
                [['mcc_plant_code', 'plant_code'], 'safe'],
                [['union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'task_code' => Yii::t('app', 'Task Code'),
            'task_type_code' => Yii::t('app', 'Task Type Code'),
            'form_type_code' => Yii::t('app', 'Form Type Code'),
            'task_performed_for' => Yii::t('app', 'Task Performed For'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'task_datetime' => Yii::t('app', 'Task Datetime'),
            'is_cancel' => Yii::t('app', 'Is Cancel'),
            'user_code' => Yii::t('app', 'User Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'status' => Yii::t('app', 'Status'),
            'is_notified' => Yii::t('app', 'Is Notified'),
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
        ];
    }

}
