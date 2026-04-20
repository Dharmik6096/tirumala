<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_tax_history".
 *
 * @property integer $id
 * @property integer $tax_code
 * @property integer $tax_group_code
 * @property string $tax_name
 * @property integer $is_active
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblTaxHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_tax_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['tax_code', 'tax_group_code', 'is_active', 'originating_type'], 'safe'],
                [['tax_name', 'union_code', 'created_by', 'updated_by', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['created_at', 'updated_at', 'history_created_at', 'ref_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'tax_code' => Yii::t('app', 'Tax Code'),
            'tax_group_code' => Yii::t('app', 'Tax Group Code'),
            'tax_name' => Yii::t('app', 'Tax Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
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

}
