<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * This is the model class for table "tbl_animal_inspector_applicability".
 *
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
 */
class TblAnimalInspectorApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_animal_inspector_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['originating_org_code', 'originating_org_type', 'applicable_code', 'applicable_for', 'created_by', 'updated_by', 'union_code', 'created_at', 'updated_at', 'animal_inspector_code', 'is_active', 'originating_type'], 'safe'],
                [['is_active'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

}
