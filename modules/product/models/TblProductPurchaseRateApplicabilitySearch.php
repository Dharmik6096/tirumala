<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductPurchaseRateApplicability;

/**
 * TblProductPurchaseRateApplicabilitySearch represents the model behind the search form about `app\modules\product\models\TblProductPurchaseRateApplicability`.
 */
class TblProductPurchaseRateApplicabilitySearch extends TblProductPurchaseRateApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_purchase_rate_applicability_code', 'wef_date', 'product_purchase_rate_code', 'product_code', 'applicable_code', 'applicable_for', 'applicable_type', 'union_code', 'mcc_plant_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['purchase_rate'], 'safe'],
                [['originating_type'], 'safe'],
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
        $query = TblProductPurchaseRateApplicability::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['customerTypeFor']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['tbl_product_purchase_rate_applicability.product_purchase_rate_code' => $this->product_purchase_rate_code]);
        if (!empty($this->wef_date))
            $query->andFilterWhere(['like', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_for])
                ->andFilterWhere(['like', 'tbl_product_purchase_rate_applicability.applicable_code', $this->applicable_code]);

        return $dataProvider;
    }

}
