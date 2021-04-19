<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\collection\models\TblCollectionPenaltyType;

/**
 * This is the model class for table "tbl_collection_penalty_rate".
 *
 * @property string $penalty_rate_code
 * @property string $penalty_rate
 * @property string $penalty_type
 * @property string $union_code
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblCollectionPenaltyRate extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_collection_penalty_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['penalty_rate_code', 'union_code', 'penalty_rate', 'penalty_type', 'wef_date'], 'required'],
            [['penalty_rate'], 'number'],
            [['created_at', 'updated_at', 'wef_date'], 'safe'],
            [['originating_type'], 'integer'],
            [['penalty_type', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['union_code'], 'string', 'max' => 3],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'penalty_rate_code' => Yii::t('app', 'Penalty Rate Code'),
            'penalty_rate' => Yii::t('app', 'Rate'),
            'penalty_type' => Yii::t('app', 'Type'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPenaltyType() {
        return $this->hasOne(TblCollectionPenaltyType::className(), ['penalty_type_code' => 'penalty_type']);
    }

}
