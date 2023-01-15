<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\welfarescheme\models\TblSchemeCriteria;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_scheme_master".
 *
 * @property integer $scheme_id
 * @property string $scheme_name
 * @property string $start_date
 * @property string $end_date
 * @property string $remarks
 * @property integer $is_active
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $applicable_for
 */
class TblSchemeMaster extends \app\models\ChildModel {

    public $min_pouring_day, $min_pouring_qty, $scheme_value, $wef_date;

    public static function tableName() {
        return 'tbl_scheme_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['wef_date', 'min_pouring_day', 'min_pouring_qty', 'scheme_value', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'union_code', 'scheme_name', 'remarks', 'start_date', 'end_date', 'created_at', 'updated_at', 'is_active', 'originating_type'], 'safe'],
                [['is_active', 'originating_type'], 'integer'],
                [['is_active'], 'default', 'value' => 1],
                [['scheme_name', 'start_date', 'union_code', 'min_pouring_day', 'min_pouring_qty', 'scheme_value', 'applicable_for'], 'required'],
                [['end_date'], 'customValidate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'scheme_id' => Yii::t('app', 'Scheme ID'),
            'scheme_name' => Yii::t('app', 'Scheme Name'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Org Code'),
            'originating_org_code' => Yii::t('app', 'Scheme ID'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'min_pouring_day' => Yii::t('app', 'Min Pouring Day'),
            'min_pouring_qty' => Yii::t('app', 'Min Pouring Qty'),
            'scheme_value' => Yii::t('app', 'Scheme Value'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
        ];
    }

    public function customValidate($attribute, $params) {

        if (!empty($this->start_date) && !empty($this->end_date)) {

            if ($this->end_date < $this->start_date) {
                $this->addError($attribute, Yii::t('app/validation', 'End Date Must be Greater Than Start Date.'));
                return false;
            }
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getUnionSchemeList($unionCode, $date) {
        $value = $this->getUnionScheme($unionCode, $date);
        return ArrayHelper::map($value, 'scheme_id', 'scheme_name');
    }

    public function getUnionScheme($unionCode = [], $date = NULL) {
        $query = $this->find()->select(['scheme_id', 'scheme_name'])->where(['is_active' => 1]);
        $query->andFilterWhere(['union_code' => $unionCode]);
        if (!empty($date)) {
            $date = date('Y-m-d', strtotime($date));
            $query->where(['<=', 'start_date', $date]);
            $query->andWhere(['OR', ['>=', 'end_date', $date], ['IS', 'end_date', NULL]]);
        }
        return $query->all();
    }

    public function getDefaultCriteriaDetail() {
        return TblSchemeCriteria::find()
                        ->where(['scheme_id' => $this->scheme_id])->orderBy('wef_date desc')->one();
    }

}
