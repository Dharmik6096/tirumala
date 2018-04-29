<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblDcs;
/**
 * This is the model class for table "tbl_product_rate_applicability".
 *
 * @property string $product_rate_applicability_code
 * @property string $wef_date
 * @property string $product_rate_code
 * @property string $dcs_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblProductRateApplicability extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wef_date'], 'required'],
            [['product_rate_applicability_code','dcs_code'], 'safe'],
            [['product_rate_applicability_code', 'union_code', 'created_by', 'updated_by'], 'string'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['dcs_code'],'required','message'=>'You must select atleast one society.'],
            //[['product_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProductRate::className(), 'targetAttribute' => ['product_rate_code' => 'product_rate_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_rate_applicability_code' => 'Rate App Code',
            'wef_date' => 'Wef Date',
            'product_rate_code' => 'Product Rate Code',
            'dcs_code' => 'Society Name',
            'union_code' => 'Union Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @inheritdoc
     * @return TblProductRateApplicabilityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblProductRateApplicabilityQuery(get_called_class());
    }
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
    
     public function getProductRateCode()
    {
        return $this->hasOne(TblProductRate::className(), ['product_rate_code' => 'product_rate_code']);
    }
}
