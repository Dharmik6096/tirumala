<?php

namespace app\modules\complaint\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_complain_escalation".
 *
 * @property integer $complain_escalation_code
 * @property string $union_code
 * @property string $escalation_name
 * @property string $escalation_remarks
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblComplainEscalation extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_escalation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'escalation_name', 'escalation_remarks', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'is_active', 'originating_type'], 'safe'],
                [['escalation_name', 'union_code'], 'required'],
                [['is_active'], 'default', 'value' => 1],
                [['escalation_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complain_escalation_code' => Yii::t('app', 'Complain Escalation Code'),
            'union_code' => Yii::t('app', 'Union'),
            'escalation_name' => Yii::t('app', 'Escalation Name'),
            'escalation_remarks' => Yii::t('app', 'Escalation Remarks'),
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

}
