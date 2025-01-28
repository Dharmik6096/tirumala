<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_dispatch_center_history".
 *
 * @property integer $id
 * @property string $dispatch_center_code
 * @property string $dispatch_center_name
 * @property string $dispatch_center_type_code
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
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
 * @property string $item_code
 */
class TblDispatchCenterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dispatch_center_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['item_code', 'originating_org_code', 'originating_org_type', 'operation_type', 'created_by', 'updated_by', 'dispatch_center_name', 'dispatch_center_code', 'dispatch_center_type_code', 'union_code', 'is_active', 'originating_type', 'created_at', 'history_created_at', 'updated_at', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'dispatch_center_code' => Yii::t('app', 'Dispatch Center Code'),
            'dispatch_center_name' => Yii::t('app', 'Dispatch Center Name'),
            'dispatch_center_type_code' => Yii::t('app', 'Dispatch Center Type Code'),
            'union_code' => Yii::t('app', 'Union Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
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
            'item_code' => Yii::t('app', 'Item Code'),
        ];
    }

}
