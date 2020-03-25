<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblHeadLoadApplicability;

/**
 * TblHeadLoadApplicabilitySearch represents the model behind the search form about `app\modules\vsp\models\TblHeadLoadApplicability`.
 */
class TblHeadLoadApplicabilitySearch extends TblHeadLoadApplicability {

    public $name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //  [['code', 'created_at', 'created_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'head_load_code', 'sub_center_code'], 'safe'],
            // [['is_delete'], 'boolean'],
                [['wef_date', 'applicable_for', 'applicable_code', 'name'], 'safe'],
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
        $query = TblHeadLoadApplicability::find();
        $query->where(['head_load_code' => $this->head_load_code]);
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

        if (!empty($this->wef_date))
            $query->andFilterWhere(['cast(tbl_head_load_applicability.wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['or',
                ['like', 'tbl_dcs.dcs_name', $this->name],
                ['like', 'tbl_customer_master.customer_name', $this->name],
                ['like', 'tbl_plant.name', $this->name],
                ['like', 'tbl_mcc_plant.name', $this->name],
                ['like', 'tbl_bmc.bmc_name', $this->name]
        ]);

        // grid filtering conditions

        $query->andFilterWhere(['like', 'tbl_head_load_applicability.applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_for])
//                ->andFilterWhere(['like', 'tbl_dcs_purchase_rate_applicability.applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }

}
