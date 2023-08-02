<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\product\models\TblProduct;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_indent_product".
 *
 * @property string $indent_product_code
 * @property string $union_code
 * @property string $product_code
 * @property string $qty
 * @property string $is_mcc
 * @property string $is_warehouse
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
class TblIndentProduct extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_indent_product';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['indent_product_code'], 'required'],
            [['union_code', 'product_code', 'is_mcc', 'is_warehouse', 'qty'], 'safe'],
            [['union_code', 'product_code', 'is_mcc', 'is_warehouse'], 'required'],
            [['qty'], 'required', 'when' => function ($model) {
                    return $model->is_warehouse == 1;
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblindentproduct-is_warehouse').is(':checked'); 
          }"],
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['qty'], 'number'],
            [['product_code'], 'unique', 'targetAttribute' => ['product_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'indent_product_code' => Yii::t('app', 'Indent Product Code'),
            'union_code' => Yii::t('app', 'Union'),
            'product_code' => Yii::t('app', 'Product'),
            'qty' => Yii::t('app', 'Minimum Qty.'),
            'is_mcc' => Yii::t('app', 'Is Mcc'),
            'is_warehouse' => Yii::t('app', 'Is Warehouse'),
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getProductList($unionCode, $indent_type) {
        $query = $this->find()->select(['product_code'])
                ->where(['union_code' => $unionCode]);

        if ($indent_type == 'mcc') {
            $query->andWhere(['is_mcc' => 1]);
        }
        if ($indent_type == 'warehouse') {
            $query->andWhere(['is_warehouse' => 1]);
        }
        $value = $query->all();

        $data = ArrayHelper::map($value, 'product_code', function($value) {
                    return Yii::$app->general->getforeignkey($value->productCode, 'product_name');
                });
        return $data;
    }

    public function getIndentProductList($unionCode, $indent_type, $product_code) {
        $query = $this->find()->select(['product_code'])
                ->where(['union_code' => $unionCode, 'product_code' => $product_code]);

        if ($indent_type == 'mcc') {
            $query->andWhere(['is_mcc' => 1]);
        }
        if ($indent_type == 'warehouse') {
            $query->andWhere(['is_warehouse' => 1]);
        }
        return $query->one();
    }

}
