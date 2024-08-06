<?php

namespace app\modules\sms\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_alert_rule_master".
 *
 * @property integer $rule_code
 * @property string $rule_name
 * @property string $union_code
 * @property string $sp_check
 * @property string $sp_name
 * @property string $sp_param
 * @property string $next_date
 * @property string $add_days
 * @property string $template_type
 * @property string $result_column
 * @property string $only_for
 * @property integer $is_trigger
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAlertRuleMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_alert_rule_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'add_days', 'template_type', 'result_column', 'next_date', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_trigger', 'is_active', 'rule_name', 'sp_check', 'sp_name', 'sp_param', 'only_for'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rule_code' => Yii::t('app', 'Rule Code'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'union_code' => Yii::t('app', 'Union'),
            'sp_check' => Yii::t('app', 'Sp Check'),
            'sp_name' => Yii::t('app', 'Sp Name'),
            'sp_param' => Yii::t('app', 'Sp Param'),
            'next_date' => Yii::t('app', 'Next Date'),
            'add_days' => Yii::t('app', 'Add Days'),
            'template_type' => Yii::t('app', 'Template Type'),
            'result_column' => Yii::t('app', 'Result Column'),
            'only_for' => Yii::t('app', 'Only For'),
            'is_trigger' => Yii::t('app', 'Is Trigger'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
