<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductRateApplicability;

/**
 * TblProductRateApplicabilitySearch represents the model behind the search form about `app\modules\product\models\TblProductRateApplicability`.
 */
class TblProductRateApplicabilitySearch extends TblProductRateApplicability {

    public $name;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_rate_applicability_code', 'product_code', 'originating_type'], 'safe'],
                [['wef_date', 'product_rate_code', 'dcs_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'mcc_plant_code', 'applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['rate', 'rate_two', 'name'], 'safe'],
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
        $query = TblProductRateApplicability::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['customerTypeFor', 'plantCode', 'mccPlantCode', 'bmcCode', 'dispDcsCode', 'mainCustomerCode']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['product_rate_code' => $this->product_rate_code]);
        if (!empty($this->wef_date))
            $query->andFilterWhere(['cast(wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['or',
                ['like', 'tbl_dcs.dcs_name', $this->name],
                ['like', 'tbl_customer_master.customer_name', $this->name],
                ['like', 'tbl_plant.name', $this->name],
                ['like', 'tbl_mcc_plant.name', $this->name],
                ['like', 'tbl_bmc.bmc_name', $this->name]
        ]);

        $query->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_for])
                ->andFilterWhere(['like', 'tbl_product_rate_applicability.applicable_code', $this->applicable_code]);

        return $dataProvider;
    }

}
