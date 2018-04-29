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
 */
class TblProductRateApplicabilityHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product_rate_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id'], 'required'],
//            [['id'], 'integer'],
//            [['rate_app_code', 'created_by', 'updated_by', 'dcs_code', 'product_rate_code', 'union_code', 'operation_type'], 'string'],
            [['id', 'rate_app_code', 'created_by', 'updated_by', 'dcs_code', 'product_rate_code', 'union_code', 'operation_type', 'created_at', 'updated_at', 'wef_date', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rate_app_code' => 'Rate App Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'wef_date' => 'Wef Date',
            'dcs_code' => 'Dcs Code',
            'product_rate_code' => 'Product Rate Code',
            'union_code' => 'Union Code',
            'history_created_at' => 'History Created At',
            'operation_type' => 'Operation Type',
        ];
    }
}
