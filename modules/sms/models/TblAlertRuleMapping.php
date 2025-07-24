<?php

namespace app\modules\sms\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\sms\models\TblAlertRuleMaster;
use app\modules\general\models\TblDepartment;

/**
 * This is the model class for table "tbl_alert_rule_mapping".
 *
 * @property integer $mapping_code
 * @property integer $rule_code
 * @property string $department_id
 * @property string $module_name
 * @property string $organization_type
 * @property string $module_code
 * @property string $result_key
 * @property integer $level
 * @property integer $frequency
 * @property integer $interval
 * @property integer $is_active
 * @property string $only_for
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblAlertRuleMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_alert_rule_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'rule_code', 'level', 'frequency', 'interval', 'is_active', 'originating_type', 'module_name', 'organization_type', 'module_code', 'result_key', 'only_for', 'department_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['union_code', 'rule_code', 'organization_type', 'department_id'], 'required'],
                [['is_active', 'frequency', 'level'], 'default', 'value' => '1'],
                [['interval'], 'default', 'value' => '0'],
                [['rule_code'], 'checkUnique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mapping_code' => Yii::t('app', 'Mapping Code'),
            'rule_code' => Yii::t('app', 'Rule'),
            'department_id' => Yii::t('app', 'Department'),
            'module_name' => Yii::t('app', 'Module Name'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'module_code' => Yii::t('app', 'Module Code'),
            'union_code' => Yii::t('app', 'Union'),
            'result_key' => Yii::t('app', 'Result Key'),
            'level' => Yii::t('app', 'Level'),
            'frequency' => Yii::t('app', 'Frequency'),
            'interval' => Yii::t('app', 'Interval'),
            'is_active' => Yii::t('app', 'Is Active'),
            'only_for' => Yii::t('app', 'Only For'),
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
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getRuleCode() {
        return $this->hasOne(TblAlertRuleMaster::className(), ['rule_code' => 'rule_code']);
    }

    public function getDepartmentCode() {
        return $this->hasOne(TblDepartment::className(), ['department_id' => 'department_id']);
    }

    public function checkUnique($attribute) {
        $result = $this->find()->select(['tbl_alert_rule_master.rule_name', 'tbl_unions.union_name', 'tbl_department.department'])
                        ->innerJoin('tbl_alert_rule_master', 'tbl_alert_rule_master.rule_code = tbl_alert_rule_mapping.rule_code')
                        ->innerJoin('tbl_department', 'tbl_department.department_id = tbl_alert_rule_mapping.department_id')
                        ->innerJoin('tbl_unions', 'tbl_unions.union_code = tbl_alert_rule_mapping.union_code')
                        ->where(['tbl_alert_rule_mapping.rule_code' => $this->rule_code, 'tbl_alert_rule_mapping.union_code' => $this->union_code, 'tbl_alert_rule_mapping.organization_type' => $this->organization_type, 'tbl_alert_rule_mapping.department_id' => $this->department_id])->asArray()->one();
        if (!empty($result)) {
            $this->addError($attribute, Yii::t('app/validation', $result['union_name'] . ',' . $result['rule_name'] . ',' . $result['department'] . ' and ' . $this->organization_type . ' combination has already been taken.'));
            return false;
        }
    }

}
