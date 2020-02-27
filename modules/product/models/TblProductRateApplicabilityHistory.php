<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_rate_applicability_history".
 *
 * @property integer $id
 * @property string $rate_app_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property string $dcs_code
 * @property string $product_rate_code
 * @property string $union_code
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $product_code
 * @property double $rate
 * @property double $rate_two
 * @property string $mcc_plant_code
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblProductRateApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_rate_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_code', 'originating_type', 'is_member_rate'], 'safe'],
                [['rate_app_code', 'created_by', 'updated_by', 'dcs_code', 'product_rate_code', 'union_code', 'operation_type', 'mcc_plant_code', 'applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['created_at', 'updated_at', 'wef_date', 'history_created_at'], 'safe'],
                [['rate', 'rate_two'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'rate_app_code' => Yii::t('app', 'Rate App Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'product_rate_code' => Yii::t('app', 'Product Rate Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'product_code' => Yii::t('app', 'Product Code'),
            'rate' => Yii::t('app', 'Rate'),
            'rate_two' => Yii::t('app', 'Rate Two'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
