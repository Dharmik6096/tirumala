<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_rate_history".
 *
 * @property integer $id
 * @property integer $product_sale_rate_code
 * @property integer $product_code
 * @property double $sale_rate
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 */
class TblProductSaleRateHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_rate_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_sale_rate_code', 'product_code', 'sale_rate', 'wef_date', 'created_at', 'history_created_at', 'updated_at', 'created_by', 'operation_type', 'updated_by', 'union_code', 'is_member_rate', 'commission','rdo_commission'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'originating_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_sale_rate_code' => Yii::t('app', 'Rate Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'sale_rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

}
