<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_animal_inspector_applicability_history".
 *
 * @property integer $id
 * @property integer $animal_inspector_applicability_code
 * @property integer $animal_inspector_code
 * @property string $union_code
 * @property string $applicable_code
 * @property string $applicable_for
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
class TblAnimalInspectorApplicabilityHistory extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_animal_inspector_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['operation_type', 'originating_org_code', 'originating_org_type', 'applicable_code', 'applicable_for', 'created_by', 'updated_by', 'history_created_by', 'union_code', 'created_at', 'updated_at', 'history_created_at', 'animal_inspector_applicability_code', 'animal_inspector_code', 'is_active', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'animal_inspector_applicability_code' => Yii::t('app', 'Animal Inspector Applicability Code'),
            'animal_inspector_code' => Yii::t('app', 'Animal Inspector Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
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
