<?php

namespace app\modules\veterinary\models;

use Yii;
use app\modules\veterinary\models\TblSymptomMaster;

/**
 * This is the model class for table "tbl_medicine_symptom_mapping".
 *
 * @property integer $medicine_symptom_id
 * @property integer $medicine_id
 * @property integer $symptom_id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMedicineSymptomMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_medicine_symptom_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_id', 'symptom_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'medicine_symptom_id' => Yii::t('app', 'Medicine Symptom ID'),
            'medicine_id' => Yii::t('app', 'Medicine ID'),
            'symptom_id' => Yii::t('app', 'Symptom ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
    
    public static function getSymptoms($code) {
        $selectedSymptomIds = self::find()->select('symptom_id')->where(['medicine_id' => $code->medicine_id])->asArray()->all();
        $selectedSymptomIds = array_column($selectedSymptomIds, 'symptom_id');
        $results = TblSymptomMaster::find()->select(['symptom_id', 'symptom_name'])->where(['not in', 'symptom_id', $selectedSymptomIds])->andWhere(['is_active' => 1])->asArray()->all();
        $selected = [];
        if (!empty($results)) {
            foreach ($results as $row) {
                if (in_array($row['symptom_id'], $selectedSymptomIds, true)) {
                    $selected[] = $row['symptom_id'] . '-' . $row['symptom_name'];
                }
            }
        }
        return ['results' => $results, 'selected' => $selected];
    }
    
    public function getSymptom() {
        return $this->hasOne(TblSymptomMaster::className(), ['symptom_id' => 'symptom_id']);
    }

}
