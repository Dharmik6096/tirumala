<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProduct;

/**
 * TblProductSearch represents the model behind the search form about `app\modules\product\models\TblProduct`.
 */
class TblProductSearch extends TblProduct {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active'], 'integer'],
                [['product_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'unit_code', 'product_group_code', 'product_name', 'product_desc', 'created_at', 'created_by', 'updated_at', 'updated_by', 'local_name', 'union_code', 'ref_code', 'is_inhouse', 'is_inclusive_tax', 'is_saleable', 'is_indent', 'tax_code', 'dpu_product_code', 'is_dpu_product', 'x_col3', 'is_milk', 'milk_type', 'purchase_ledger', 'sale_ledger', 'stock_ledger', 'local_sale_ledger', 'other_state_tax_code', 'coupon_ledger'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = TblProduct::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'unitCode', 'productGroupCode', 'taxCode', 'purchaseLedgerCode as purchase_led', 'saleLedgerCode as sale_led', 'stateTaxCode as state_tax', 'localSaleLedgerCode as local_sale_led', 'stockLedgerCode as stock_led', 'couponLedgerCode as coupon_led']);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product.is_active' => $this->is_active,
            'tbl_product.is_inhouse' => $this->is_inhouse,
            'tbl_product.is_inclusive_tax' => $this->is_inclusive_tax,
            'tbl_product.is_saleable' => $this->is_saleable,
            'tbl_product.is_indent' => $this->is_indent,
            'tbl_product.is_dpu_product' => $this->is_dpu_product,
            'tbl_product.x_col3' => $this->x_col3,
            'tbl_product.is_milk' => $this->is_milk,
        ]);

        $query->andFilterWhere(['like', 'tbl_product.product_name', $this->product_name])
                ->andFilterWhere(['like', 'tbl_product.product_code', $this->product_code])
                ->andFilterWhere(['like', 'tbl_product.product_desc', $this->product_desc])
                ->andFilterWhere(['like', 'tbl_product_group.product_group_name', $this->product_group_code])
                ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
                ->andFilterWhere(['like', 'tbl_units.unit_name', $this->unit_code])
                ->andFilterWhere(['like', 'tbl_plant.name', $this->plant_code])
                ->andFilterWhere(['like', 'tbl_mcc_plant.name', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_product.local_name', $this->local_name])
                ->andFilterWhere(['like', 'tbl_tax.tax_name', $this->tax_code])
                ->andFilterWhere(['like', 'tbl_product.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_product.dpu_product_code', $this->dpu_product_code])
                ->andFilterWhere(['like', 'state_tax.tax_name', $this->other_state_tax_code])
                ->andFilterWhere(['like', 'local_sale_led.ledger_name', $this->local_sale_ledger])
                ->andFilterWhere(['like', 'stock_led.ledger_name', $this->stock_ledger])
                ->andFilterWhere(['like', 'sale_led.ledger_name', $this->sale_ledger])
                ->andFilterWhere(['like', 'purchase_led.ledger_name', $this->purchase_ledger])
                ->andFilterWhere(['like', 'coupon_led.ledger_name', $this->coupon_ledger])
                ->andFilterWhere(['like', 'tbl_product.milk_type', $this->milk_type]);

        return $dataProvider;
    }

}
