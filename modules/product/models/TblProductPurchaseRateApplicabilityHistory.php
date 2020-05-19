<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_purchase_rate_applicability_history".
 *
 * @property integer $id
 * @property string $product_purchase_rate_applicability_code
 * @property string $wef_date
 * @property string $product_purchase_rate_code
 * @property string $product_code
 * @property string $purchase_rate
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $union_code
 * @property string $mcc_plant_code
 * @property string $dcs_code
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
class TblProductPurchaseRateApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_purchase_rate_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_purchase_rate_applicability_code', 'product_purchase_rate_code', 'product_code', 'applicable_code', 'applicable_for', 'applicable_type', 'union_code', 'mcc_plant_code', 'dcs_code', 'created_by', 'updated_by', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['purchase_rate'], 'safe'],
                [['originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_purchase_rate_applicability_code' => Yii::t('app', 'Product Purchase Rate Applicability Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'product_purchase_rate_code' => Yii::t('app', 'Product Purchase Rate Code'),
            'product_code' => Yii::t('app', 'Product Code'),
            'purchase_rate' => Yii::t('app', 'Purchase Rate'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
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
