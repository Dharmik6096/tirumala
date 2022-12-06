<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\welfarescheme\models\TblSchemeMaster;

/**
 * This is the model class for table "tbl_scheme_criteria".
 *
 * @property integer $scheme_criteria_id
 * @property integer $scheme_id
 * @property string $wef_date
 * @property string $min_pouring_day
 * @property string $min_pouring_qty
 * @property string $scheme_value
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSchemeCriteria extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_criteria';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['scheme_id', 'originating_type'], 'integer'],
                [['originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'union_code', 'min_pouring_day', 'min_pouring_qty', 'scheme_value', 'scheme_id', 'originating_type', 'wef_date', 'created_at', 'updated_at'], 'safe'],
                [['min_pouring_day', 'min_pouring_qty', 'scheme_value', 'wef_date'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'scheme_criteria_id' => 'Scheme Criteria ID',
            'scheme_id' => 'Scheme ID',
            'wef_date' => 'Wef Date',
            'min_pouring_day' => 'Min Pouring Day',
            'min_pouring_qty' => 'Min Pouring Qty',
            'scheme_value' => 'Scheme Value',
            'union_code' => 'Union Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
        ];
    }

    public function getSchemeId() {
        return $this->hasOne(TblSchemeMaster::className(), ['scheme_id' => 'scheme_id']);
    }

}
