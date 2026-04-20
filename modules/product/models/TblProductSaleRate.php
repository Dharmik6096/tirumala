<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\product\models\TblProduct;
use app\modules\organisation\models\TblUnions;
use app\modules\syncutility\models\TblSentbox;

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
class TblProductSaleRate extends \app\models\ChildModel {

    public $is_sentbox = TRUE;
    public $import_union_code, $import_eipl_code, $import_key_pattern;

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
        $main_rules = [
            [['product_code', 'sale_rate', 'wef_date', 'union_code'], 'required', 'except' => ['androidsync']],
//                [['product_code'], 'integer'],
            [['sale_rate'], 'number', 'min' => 0, 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."10"')],
            [['wef_date', 'created_at', 'updated_at', 'product_sale_rate_code', 'union_code', 'is_member_rate', 'commission','rdo_commission'], 'safe'],
            [['created_by', 'updated_by'], 'string'],
            [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code'], 'except' => ['androidsync']],
            ['wef_date', 'unique', 'message' => Yii::t('app/validation', 'Rate is already taken on this WEF Date.'), 'when' => function($model) {
                    $data = $this->find()->where(['union_code' => $model->union_code, 'product_code' => $model->product_code, 'wef_date' => date('Y-m-d', strtotime($this->wef_date)), 'is_member_rate' => $model->is_member_rate])->andWhere(['<>', 'product_sale_rate_code', $model->product_sale_rate_code])->count();
                    return ($data == 1) ? true : false;
                }],
            [['commission', 'rdo_commission'], 'required', 'when' => function ($model) {
                    return $model->is_member_rate == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproductsalerate-is_member_rate').is(':checked'); 
               }"
            ],

            [['commission', 'rdo_commission'], 'number', 'min' => 0, 'when' => function ($model) {
                    return $model->is_member_rate == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproductsalerate-is_member_rate').is(':checked'); 
               }"],
            [['is_member_rate', 'commission', 'rdo_commission'], 'default', 'value' => 0],
            [['commission'], 'validateCommission', 'skipOnEmpty' => false],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'originating_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'product_code', 'rate_wharehouse'], 'safe'],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code'], 'on' => ['importCsv']],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['wef_date'], 'convertDate', 'on' => ['importCsv']],
            [['wef_date'], 'validateDate', 'on' => ['importCsv']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblProductSaleRate', $this->form_validation_type, $this->import_eipl_code);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
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
            'commission' => Yii::t('app', 'Society Commission'),
            'rdo_commission' => Yii::t('app', 'RDO Commission'),
            'is_member_rate' => Yii::t('app', 'Is Member Rate'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getProductRateAppCode() {
        return $this->hasMany(TblProductSaleRateApplicability::className(), ['product_sale_rate_code' => 'product_sale_rate_code']);
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
     * @return TblProductSaleRateQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblProductSaleRateQuery(get_called_class());
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
        $app = TblProductSaleRateApplicability::find()->where(['product_sale_rate_code' => $this->product_sale_rate_code])->one();
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

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        if (!empty($this->is_member_rate)) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        } else {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code, '', false);
        }
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
        }
    }

    public function validateDate($attribute, $params) {
        if (empty($this->getErrors())) {
            if ($this->wef_date < date('Y-m-d')) {
                $this->addError($attribute, Yii::t('app/validation', 'Wef Date Must Not Allow Past Date'));
                return false;
            }
        }
    }

}
