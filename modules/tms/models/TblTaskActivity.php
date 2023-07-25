<?php

namespace app\modules\tms\models;

use Yii;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblRouteMapping;

/**
 * This is the model class for table "tbl_task_activity".
 *
 * @property integer $task_activity_code
 * @property integer $task_code
 * @property string $user_code
 * @property string $route_code
 * @property string $module_type
 * @property string $module_code
 * @property integer $form_type_code
 * @property string $contact_person
 * @property string $contact_person_mobile_no
 * @property string $task_datetime
 * @property string $activity_datetime
 * @property string $status
 * @property string $remarks
 * @property string $form_data
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblTaskActivity extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_task_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['status'], 'default', 'value' => 'OPEN'],
                [['task_code', 'form_type_code', 'originating_type'], 'safe'],
                [['task_datetime', 'activity_datetime', 'created_at', 'updated_at'], 'safe'],
                [['form_data'], 'safe'],
                [['user_code', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['route_code'], 'string', 'max' => 12],
                [['module_type', 'status'], 'string', 'max' => 10],
                [['module_code'], 'string', 'max' => 20],
                [['contact_person'], 'string', 'max' => 100],
                [['contact_person_mobile_no', 'remarks'], 'string', 'max' => 255],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'task_activity_code' => Yii::t('app', 'Task Activity Code'),
            'task_code' => Yii::t('app', 'Task Code'),
            'user_code' => Yii::t('app', 'User Code'),
            'route_code' => Yii::t('app', 'Route'),
            'module_type' => Yii::t('app', 'Type'),
            'module_code' => Yii::t('app', 'Code'),
            'form_type_code' => Yii::t('app', 'Form Type Code'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'contact_person_mobile_no' => Yii::t('app', 'Contact Person No.'),
            'task_datetime' => Yii::t('app', 'Task Datetime'),
            'activity_datetime' => Yii::t('app', 'Activity Datetime'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'form_data' => Yii::t('app', 'Form Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'module_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'module_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'module_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

}
