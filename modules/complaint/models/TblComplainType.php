<?php

namespace app\modules\complaint\models;

use Yii;
use app\modules\complaint\models\TblComplainEscalation;

/**
 * This is the model class for table "tbl_complain_type".
 *
 * @property integer $complain_type_code
 * @property integer $complain_type
 * @property string $complain_escalation_code
 * @property string $complain_for
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblComplainType extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complain_for', 'complain_type', 'is_active', 'originating_type', 'created_at', 'updated_at', 'complain_escalation_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['complain_for', 'complain_type', 'complain_escalation_code'], 'required'],
                [['is_active'], 'default', 'value' => 1],
                [['complain_type'], 'string'],
                [['complain_type'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complain_type_code' => Yii::t('app', 'Complain Type Code'),
            'complain_type' => Yii::t('app', 'Complain Type'),
            'complain_escalation_code' => Yii::t('app', 'Complain Escalation Name'),
            'complain_for' => Yii::t('app', 'Complain For'),
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

    public function getComplainEscalationCode() {
        return $this->hasOne(TblComplainEscalation::className(), ['complain_escalation_code' => 'complain_escalation_code']);
    }

    public function levelStages($complain_type_code) {
        return $this->find()
                        ->select(['tbl_complain_escalation_txn.*'])
                        ->innerJoin('tbl_complain_escalation', 'tbl_complain_escalation.complain_escalation_code = tbl_complain_type.complain_escalation_code')
                        ->innerJoin('tbl_complain_escalation_txn', 'tbl_complain_escalation_txn.complain_escalation_code = tbl_complain_escalation.complain_escalation_code')
                        ->where(['tbl_complain_type.complain_type_code' => $complain_type_code])
                        ->orderBy('tbl_complain_escalation_txn.level asc')
                        ->asArray()
                        ->all();
    }

}
