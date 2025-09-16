<?php

namespace app\modules\veterinary\models;

use Yii;

/**
 * This is the model class for table "tbl_symptom_master".
 *
 * @property integer $symptom_id
 * @property string $symptom_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblSymptomMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_symptom_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['symptom_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'is_active'], 'safe'],
            [['symptom_name'], 'required'],
            [['symptom_name'], 'unique'],
            [['is_active'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'symptom_id' => Yii::t('app', 'Symptom ID'),
            'symptom_name' => Yii::t('app', 'Symptom Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
