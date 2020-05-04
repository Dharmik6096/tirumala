<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\product\models\TblProduct;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_product_rate".
 *
 * @property integer $product_sale_rate_code
 * @property integer $product_code
 * @property double $sale_rate
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblProduct $productCode
 */
class TblProductRate extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_code', 'sale_rate', 'wef_date', 'union_code'], 'required'],
                [['product_code'], 'integer'],
                [['sale_rate'], 'number', 'min' => 0, 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."10"')],
                [['wef_date', 'created_at', 'updated_at', 'product_sale_rate_code', 'union_code', 'is_member_rate', 'commission'], 'safe'],
                [['created_by', 'updated_by'], 'string'],
                [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code']],
                ['wef_date', 'unique', 'message' => Yii::t('app/validation', 'Rate is already taken on this WEF Date.'), 'when' => function($model) {
                    $data = $this->find()->where(['union_code' => $model->union_code, 'product_code' => $model->product_code, 'wef_date' => Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT)])->andWhere(['<>', 'product_sale_rate_code', $model->product_sale_rate_code])->count();
                    return ($data == 1) ? true : false;
                }],
                [['commission'], 'required', 'when' => function ($model) {
                    return $model->is_member_rate == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproductrate-is_member_rate').is(':checked'); 
               }"
            ],
                [['commission'], 'number', 'min' => 0, 'when' => function ($model) {
                    return $model->is_member_rate == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproductrate-is_member_rate').is(':checked'); 
               }"],
                [['is_member_rate', 'commission'], 'default', 'value' => 0],
                [['commission'], 'validateCommission', 'skipOnEmpty' => false],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'originating_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_sale_rate_code' => Yii::t('app', 'Rate Code'),
            'product_code' => Yii::t('app', 'Product'),
            'sale_rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'Society Name'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'commission' => Yii::t('app', 'Commission'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getProductRateAppCode() {
        return $this->hasMany(TblProductRateApplicability::className(), ['product_sale_rate_code' => 'product_sale_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @inheritdoc
     * @return TblProductRateQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblProductRateQuery(get_called_class());
    }

    public function getMinDate() {

        $query = $this->find()->select('wef_date')->where(['product_code' => $this->product_code, 'union_code' => $this->union_code]);
        if (!empty($this->product_sale_rate_code)) {
            $query->andWhere(['<>', 'product_sale_rate_code', $this->product_sale_rate_code]);
        }
        $date = $query->orderBy(['wef_date' => SORT_DESC])->one();
        return $date ? Yii::$app->controls->view_date($date->wef_date) : date('d-m-Y');
    }

    public function disableProduct() {

        $mdate = $this->getMinDate();
        $dis = ($mdate < Yii::$app->controls->view_date($this->wef_date) || $mdate == date('d-m-Y')) ? true : false;
        return ($dis) ? TRUE : FALSE;
    }

    public function productList() {
        $rate_data = $this->find()
                ->where(['<=', 'wef_date', date('Y-m-d')])
//                ->andFilterWhere(['=', 'is_active', '1'])
                ->all();
        $product = [];
        $i = 0;
        foreach ($rate_data as $rate_code) {
            $product[$i]['product_code'] = $rate_code['product_code'];
            $product[$i]['product_name'] = Yii::$app->general->getforeignkey($rate_code->productCode, 'product_name');
            $product[$i]['product_rate'] = $rate_code['sale_rate'];
            $i++;
        }
        return $product;
    }

    public function disableDelete() {
        $app = TblProductRateApplicability::find()->where(['product_sale_rate_code' => $this->product_sale_rate_code])->one();
        if (!empty($app))
            return false;
        else
            return true;
    }

    public function validateCommission($attribute, $params) {
        if (!empty($this->is_member_rate)) {
            if ($this->commission > $this->sale_rate) {
                $this->addError($attribute, Yii::t('app/validation', 'VSP Commission must be less than Rate'));
                return false;
            }
        }
    }

}
