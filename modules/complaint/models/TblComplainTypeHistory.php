<?php

namespace app\modules\complaint\models;

use Yii;

/**
 * This is the model class for table "tbl_complain_type_history".
 *
 * @property integer $id
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblComplainTypeHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_type_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complain_type_code', 'complain_escalation_code', 'complain_type', 'complain_for', 'is_active', 'originating_type', 'created_at', 'updated_at', 'history_created_at', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'complain_type_code' => Yii::t('app', 'Complain Type Code'),
            'complain_type' => Yii::t('app', 'Complain Type'),
            'complain_escalation_code' => Yii::t('app', 'Complain Escalation Code'),
            'complain_for' => Yii::t('app', 'Complain For'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
