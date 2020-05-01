<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductPurchaseRate;

/**
 * TblProductPurchaseRateSearch represents the model behind the search form about `app\modules\product\models\TblProductPurchaseRate`.
 */
class TblProductPurchaseRateSearch extends TblProductPurchaseRate {

    public $product_rate_code_val;
    public $union_name;
    public $dcs_name;
    public $product_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_purchase_rate_code', 'product_code', 'wef_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['purchase_rate'], 'safe'],
                [['originating_type', 'product_rate_code_val', 'union_name', 'dcs_name', 'product_name'], 'safe'],
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
            $subquery = TblProductPurchaseRate::find()->select(['max(product_purchase_rate_code) As product_purchase_rate_code'])->groupBy(['product_code', 'union_code'])->all();

            $query = TblProductPurchaseRate::find()->where(['product_purchase_rate_code' => $subquery])->orderBy('wef_date DESC');
        } else {
            $query = TblProductPurchaseRate::find();
        }

        if (Yii::$app->request->get('id') != "") {
            $query->where(['tbl_product_purchase_rate.product_code' => $this->product_code]);
            $query->andwhere(['tbl_product_purchase_rate.union_code' => $this->union_code]);
//            $query->andwhere(['<>', 'product_rate_code', Yii::$app->request->get('id')]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['wef_date' => SORT_DESC]],
        ]);

        $this->load($params);

        $query->joinWith(['productCode', 'unionCode']);
        Yii::$app->general->filterByOrg($query, $this);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->wef_date))
            $query->andwhere(['wef_date' => date('Y-m-d', strtotime($this->wef_date))]);

        Yii::$app->general->filterByNumber($query, $this, ['purchase_rate']);
        // grid filtering conditions

        if (Yii::$app->request->get('id') == "") {
            $query->andFilterWhere(['like', 'product_purchase_rate_code', $this->product_purchase_rate_code])
                    ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code]);
        } else {
            $query->andFilterWhere(['like', 'product_rate_code', $this->product_rate_code_val])
                    ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
                    ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_name]);
        }

        $query->andFilterWhere(['like', 'created_by', $this->created_by]);
        return $dataProvider;
    }

}
