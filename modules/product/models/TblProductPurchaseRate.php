<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\product\models\TblProduct;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_product_purchase_rate".
 *
 * @property string $product_purchase_rate_code
 * @property string $product_code
 * @property string $purchase_rate
 * @property string $wef_date
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
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
 */
class TblProductPurchaseRate extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_purchase_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_code'], 'required', 'on' => ['androidsync']],
            [['product_code', 'purchase_rate', 'wef_date', 'union_code'], 'required'],
            [['purchase_rate'], 'number', 'min' => 0, 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."10"')],
            [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code']],
            [['product_purchase_rate_code', 'product_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['purchase_rate'], 'number'],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code'], 'on' => ['importCsv']],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['wef_date'], 'convertDate', 'on' => ['importCsv']],
            [['wef_date'], 'validateDate', 'on' => ['importCsv']],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['product_purchase_rate_code'], 'validateProductPurchaseRate', 'skipOnEmpty' => false],
            ['wef_date', 'unique', 'message' => Yii::t('app/validation', 'Rate is already taken on this WEF Date.'), 'when' => function ($model) {
                    $data = $this->find()->where(['union_code' => $model->union_code, 'product_code' => $model->product_code, 'wef_date' => Yii::$app->formatter->asDate($this->wef_date, 'php:Y-m-d')])->andWhere(['<>', 'product_purchase_rate_code', $model->product_purchase_rate_code])->count();
                    return ($data == 1) ? true : false;
                }],
            [['originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_purchase_rate_code' => Yii::t('app', 'Product Purchase Rate Code'),
            'product_code' => Yii::t('app', 'Product'),
            'purchase_rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'WEF Date'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function validateProductPurchaseRate($attribute, $params) {
        $this->product_purchase_rate_code = Yii::$app->general->getPrimaryCode($this);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getProductPurchaseRateAppCode() {
        return $this->hasMany(TblProductPurchaseRateApplicability::className(), ['product_purchase_rate_code' => 'product_purchase_rate_code']);
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

    public function getMinDate() {

        $query = $this->find()->select('wef_date')->where(['product_code' => $this->product_code, 'union_code' => $this->union_code]);
        if (!empty($this->product_purchase_rate_code)) {
            $query->andWhere(['<>', 'product_purchase_rate_code', $this->product_purchase_rate_code]);
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
                ->all();
        $product = [];
        $i = 0;
        foreach ($rate_data as $rate_code) {
            $product[$i]['product_code'] = $rate_code['product_code'];
            $product[$i]['product_name'] = Yii::$app->general->getforeignkey($rate_code->productCode, 'product_name');
            $product[$i]['product_rate'] = $rate_code['purchase_rate'];
            $i++;
        }
        return $product;
    }

    public function disableDelete() {
        $app = TblProductPurchaseRateApplicability::find()->where(['product_purchase_rate_code' => $this->product_purchase_rate_code])->one();
        if (!empty($app))
            return false;
        else
            return true;
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
