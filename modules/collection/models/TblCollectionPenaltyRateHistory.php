<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_collection_penalty_rate_history".
 *
 * @property integer $id
 * @property string $penalty_rate_code
 * @property string $penalty_rate
 * @property string $penalty_type
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblCollectionPenaltyRateHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_collection_penalty_rate_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['penalty_rate'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'wef_date'], 'safe'],
            [['originating_type'], 'safe'],
            [['penalty_rate_code'], 'safe'],
            [['penalty_type', 'created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['union_code'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'penalty_rate_code' => Yii::t('app', 'Penalty Rate Code'),
            'penalty_rate' => Yii::t('app', 'Penalty Rate'),
            'penalty_type' => Yii::t('app', 'Penalty Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
