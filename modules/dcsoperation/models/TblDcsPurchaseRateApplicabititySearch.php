<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;

/**
 * TblDcsPurchaseRateApplicabititySearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity`.
 */
class TblDcsPurchaseRateApplicabititySearch extends TblDcsPurchaseRateApplicabitity {

    public $mcc_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //   [['rate_app_code', 'created_at', 'created_by', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'purchase_rate_code', 'union_code'], 'safe'],
            //   [['is_active'], 'boolean'],
            //   [['shift_code'], 'integer'],
                [['wef_date', 'applicable_for', 'mcc_name', 'applicable_code', 'applicable_for'], 'safe'],
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
        $query = TblDcsPurchaseRateApplicabitity::find();
        $query->where(['tbl_dcs_purchase_rate_applicability.purchase_rate_code' => $this->purchase_rate_code]);
        $query->orderBy(['wef_date' => SORT_DESC]);
        // add conditions that should always apply here
        $query->joinWith(['customerMasterCode', 'dcsName', 'bmcCode', 'mccPlantCode', 'plantCode', 'customerType']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'shift_code' => $this->shift_code,
        ]);

        if (!empty($this->wef_date))
            $query->andFilterWhere(['cast(tbl_dcs_purchase_rate_applicability.wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);
        // grid filtering conditions

        $query->andFilterWhere(['or',
                ['like', 'tbl_dcs.dcs_name', $this->mcc_name],
                ['like', 'tbl_customer_master.customer_name', $this->mcc_name],
                ['like', 'tbl_plant.name', $this->mcc_name],
                ['like', 'tbl_mcc_plant.name', $this->mcc_name],
                ['like', 'tbl_bmc.bmc_name', $this->mcc_name]
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs_purchase_rate_applicability.applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_for])
//                ->andFilterWhere(['like', 'tbl_dcs_purchase_rate_applicability.applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }

}
