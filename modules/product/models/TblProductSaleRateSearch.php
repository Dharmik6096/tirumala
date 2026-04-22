<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductSaleRate;

/**
 * TblProductSaleRateSearch represents the model behind the search form about `app\modules\product\models\TblProductSaleRate`.
 */
class TblProductSaleRateSearch extends TblProductSaleRate {

    public $product_sale_rate_code_val;
    public $union_name;
    public $dcs_name;
    public $product_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//                [['is_active'], 'integer'],
//            [['rate'], 'number'],
                [['product_sale_rate_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'product_code', 'sale_rate', 'product_sale_rate_code_val', 'union_name', 'dcs_name', 'product_name', 'union_code', 'is_member_rate'], 'safe'],
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
        if (empty($this->product_code)) {
            // $query = TblProductPurchaseRate::find()->select(['tbl_product_purchase_rate.product_code','tbl_product_purchase_rate.union_code','tbl_product_purchase_rate.dcs_code']);
            $subquery = TblProductSaleRate::find()->select(['max_date = max(wef_date)', 'product_code', 'is_member_rate'])->groupBy(['product_code', 'union_code', 'is_member_rate']); //->all();
            $query = TblProductSaleRate::find()->select(['tbl_product_sale_rate.product_sale_rate_code as product_sale_rate_code', 'tbl_product_sale_rate.union_code as union_code', 'tbl_product_sale_rate.product_code as product_code', 'tbl_product_sale_rate.sale_rate as sale_rate', 'tbl_product_sale_rate.wef_date as wef_date', 'tbl_product_sale_rate.is_member_rate as is_member_rate', 'tbl_product_sale_rate.commission as commission','tbl_product_sale_rate.rdo_commission as rdo_commission', 'tbl_product_sale_rate.rate_wharehouse'])->from(['u' => $subquery]);
            $query->join('inner join', 'tbl_product_sale_rate', 'tbl_product_sale_rate.wef_date=u.max_date and tbl_product_sale_rate.product_code=u.product_code and tbl_product_sale_rate.is_member_rate=u.is_member_rate');
            $query->orderBy('wef_date DESC');
//                    ->leftJoin(['x' => $subquery], 'x.wef_date=tbl_product_sale_rate.wef_date and x.product_code = tbl_product_sale_rate.product_code'); //->where(['wef_date' => $subquery])->orderBy('wef_date DESC');
        } else {
            $query = TblProductSaleRate::find();
        }

        // add conditions that should always apply here
        if (Yii::$app->request->get('id') != "") {
            $query->where(['tbl_product_sale_rate.product_code' => $this->product_code]);
            $query->andwhere(['tbl_product_sale_rate.union_code' => $this->union_code]);
//            $query->andwhere(['<>', 'product_sale_rate_code', Yii::$app->request->get('id')]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['wef_date' => SORT_DESC]],
        ]);

        $this->load($params);

        $query->joinWith(['productCode']);

        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->wef_date))
            $query->andwhere(['wef_date' => date('Y-m-d', strtotime($this->wef_date))]);

        Yii::$app->general->filterByNumber($query, $this, ['sale_rate']);
        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
//            'tbl_product_sale_rate.is_active' => $this->is_active,
            'updated_at' => $this->updated_at,
            'tbl_product_sale_rate.is_member_rate' => $this->is_member_rate,
        ]);

        if (Yii::$app->request->get('id') == "") {
            $query->andFilterWhere(['like', 'product_sale_rate_code', $this->product_sale_rate_code])
                    ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code]);
        } else {
            $query->andFilterWhere(['like', 'product_sale_rate_code', $this->product_sale_rate_code_val])
                    ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_name]);
        }

        $query->andFilterWhere(['like', 'created_by', $this->created_by]);
        return $dataProvider;
    }

}
