<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblBmcCollectionAudit;

/**
 * TblBmcCollectionAuditSearch represents the model behind the search form about `app\modules\collection\models\TblBmcCollectionAudit`.
 */
class TblBmcCollectionAuditSearch extends TblBmcCollectionAudit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bmc_collection_audit_code', 'shift_code', 'sample_no', 'qty_mode', 'converted_qty_mode', 'no_of_can', 'qty_auto', 'qlty_auto', 'milk_type_code', 'milk_quality_type_code', 'milk_analyser_type_code', 'ws_code', 'bmc_silos_info_code', 'is_active', 'originating_type'], 'integer'],
            [['qty', 'converted_qty', 'fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'rtpl', 'amount', 'adt_value', 'tare_weight', 'gross_weight', 'scheme_rate', 'actual_rate'], 'number'],
            [['dcs_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'vehicle_no', 'route_arrival_time', 'own_bmc_code', 'own_mcc_plant_code', 'purchase_rate_code', 'customer_type', 'customer_code', 'adt_param', 'antibiotic', 'scheme_rate_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblBmcCollectionAudit::find();

        // add conditions that should always apply here

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
            'bmc_collection_audit_code' => $this->bmc_collection_audit_code,
            'shift_code' => $this->shift_code,
            'sample_no' => $this->sample_no,
            'qty' => $this->qty,
            'qty_mode' => $this->qty_mode,
            'converted_qty' => $this->converted_qty,
            'converted_qty_mode' => $this->converted_qty_mode,
            'no_of_can' => $this->no_of_can,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'clr' => $this->clr,
            'water' => $this->water,
            'protein' => $this->protein,
            'density' => $this->density,
            'lactose' => $this->lactose,
            'rtpl' => $this->rtpl,
            'amount' => $this->amount,
            'qty_auto' => $this->qty_auto,
            'qlty_auto' => $this->qlty_auto,
            'milk_type_code' => $this->milk_type_code,
            'milk_quality_type_code' => $this->milk_quality_type_code,
            'milk_analyser_type_code' => $this->milk_analyser_type_code,
            'ws_code' => $this->ws_code,
            'route_arrival_time' => $this->route_arrival_time,
            'adt_value' => $this->adt_value,
            'bmc_silos_info_code' => $this->bmc_silos_info_code,
            'tare_weight' => $this->tare_weight,
            'gross_weight' => $this->gross_weight,
            'scheme_rate' => $this->scheme_rate,
            'actual_rate' => $this->actual_rate,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'route_code', $this->route_code])
            ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'own_bmc_code', $this->own_bmc_code])
            ->andFilterWhere(['like', 'own_mcc_plant_code', $this->own_mcc_plant_code])
            ->andFilterWhere(['like', 'purchase_rate_code', $this->purchase_rate_code])
            ->andFilterWhere(['like', 'customer_type', $this->customer_type])
            ->andFilterWhere(['like', 'customer_code', $this->customer_code])
            ->andFilterWhere(['like', 'adt_param', $this->adt_param])
            ->andFilterWhere(['like', 'antibiotic', $this->antibiotic])
            ->andFilterWhere(['like', 'scheme_rate_code', $this->scheme_rate_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'x_col1', $this->x_col1])
            ->andFilterWhere(['like', 'x_col2', $this->x_col2])
            ->andFilterWhere(['like', 'x_col3', $this->x_col3])
            ->andFilterWhere(['like', 'x_col4', $this->x_col4])
            ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }
}
