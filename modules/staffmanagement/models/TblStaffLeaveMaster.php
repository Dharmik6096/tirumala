<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;
use app\modules\staffmanagement\models\TblStaffMember;

/**
 * This is the model class for table "tbl_staff_leave_master".
 *
 * @property string $staff_leave_code
 * @property string $union_code
 * @property string $leave_type
 * @property integer $leave_for
 * @property integer $is_half
 * @property integer $is_default
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblStaffLeaveMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_leave_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_leave_code', 'union_code', 'leave_for', 'leave_type'], 'required'],
            [['staff_leave_code', 'union_code', 'leave_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['leave_for', 'is_half', 'is_default', 'is_active', 'originating_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            ['leave_type', 'unique', 'targetAttribute' => ['leave_type', 'union_code', 'leave_for'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['is_half', 'is_default'], 'default', 'value' => 0],
            [['is_active'], 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_leave_code' => Yii::t('app', 'Staff Leave Code'),
            'union_code' => Yii::t('app', 'Union'),
            'leave_type' => Yii::t('app', 'Leave Type'),
            'leave_for' => Yii::t('app', 'Leave For'),
            'is_half' => Yii::t('app', 'Is Half'),
            'is_default' => Yii::t('app', 'Is Default'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getLeaveType($union, $staff) {
        $member = TblStaffMember::find()->where(['staff_member_code' => $staff])->one();
        $array = [];
        if (($member->is_on_role == 0 || !empty($member->is_on_role))) {
            $data = $this->find()
                    ->where(['union_code' => $union, 'leave_for' => $member->is_on_role]);
            $data = $data->all();
            $array = \yii\helpers\ArrayHelper::map($data, 'leave_type', function($data) {
                        return Yii::$app->general->getStaticValue($data->leave_type, 'leave_type');
                    });
        }
        return $array;
    }

}
