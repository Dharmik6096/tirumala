<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_group_history".
 *
 * @property integer $id
 * @property integer $product_group_code
 * @property string $product_group_name
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 */
class TblProductGroupHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_group_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['product_group_code', 'is_active'], 'integer'],
//            [['product_group_name', 'created_by', 'operation_type', 'updated_by', 'local_name'], 'string'],
                [['product_group_code', 'is_active', 'created_at', 'product_group_name', 'created_by', 'operation_type', 'updated_by', 'local_name', 'history_created_at', 'updated_at', 'union_code', 'unit_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'ref_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_group_code' => Yii::t('app', 'Product Group Code'),
            'product_group_name' => Yii::t('app', 'Product Group Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
        ];
    }

}
