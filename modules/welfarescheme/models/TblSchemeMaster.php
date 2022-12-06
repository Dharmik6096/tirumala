<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\organisation\models\TblUnions;
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
 */
class TblSchemeMaster extends \app\models\ChildModel {

    public $min_pouring_day, $min_pouring_qty, $scheme_value;

    public static function tableName() {
        return 'tbl_scheme_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['min_pouring_day', 'min_pouring_qty', 'scheme_value', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'union_code', 'scheme_name', 'remarks', 'start_date', 'end_date', 'created_at', 'updated_at', 'is_active', 'originating_type'], 'safe'],
                [['is_active', 'originating_type'], 'integer'],
                [['is_active'], 'default', 'value' => 1],
                [['scheme_name', 'start_date', 'end_date', 'union_code'], 'required'],
                [['end_date'], 'customValidate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'scheme_id' => 'Scheme ID',
            'scheme_name' => 'Scheme Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'remarks' => 'Remarks',
            'is_active' => 'Is Active',
            'union_code' => 'Union Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'min_pouring_day' => 'Min Pouring Day',
            'min_pouring_qty' => 'Min Pouring Qty',
            'scheme_value' => 'Scheme Value',
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

}
