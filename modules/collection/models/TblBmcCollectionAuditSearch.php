<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblBmcCollectionAudit;

/**
 * TblBmcCollectionAuditSearch represents the model behind the search form about `app\modules\collection\models\TblBmcCollectionAudit`.
 */
class TblBmcCollectionAuditSearch extends TblBmcCollectionAudit {

    public $ref_code, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift_code', 'sample_no', 'qty_mode', 'converted_qty_mode', 'no_of_can', 'qty_auto', 'qlty_auto', 'milk_type_code', 'milk_quality_type_code', 'milk_analyser_type_code', 'ws_code', 'bmc_silos_info_code', 'is_active', 'originating_type'], 'integer'],
            [['qty', 'converted_qty', 'fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'rtpl', 'amount', 'adt_value', 'tare_weight', 'gross_weight', 'scheme_rate', 'actual_rate'], 'number'],
            [['dcs_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'vehicle_no', 'route_arrival_time', 'own_bmc_code', 'own_mcc_plant_code', 'purchase_rate_code', 'customer_type', 'customer_code', 'adt_param', 'antibiotic', 'scheme_rate_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'ref_code', 'bmc_ref_code', 'date_time_of_collection'], 'safe'],
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
        $query = TblBmcCollectionAudit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['dcsCode', 'shiftCode', 'bmcCode', 'milkTypeCode', 'routeCode', 'milkQualityType', 'silosCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bmc_collection_audit', 'tbl_bmc_collection_audit', 'tbl_bmc_collection_audit');

        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_rejection.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'qty_auto' => $this->qty_auto,
            'qlty_auto' => $this->qlty_auto,
        ]);

        $query->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_quality_type.milk_quality_type_name', $this->milk_quality_type_code])
                ->andFilterWhere(['like', 'purchase_rate_code', $this->purchase_rate_code]);

        return $dataProvider;
    }

}
