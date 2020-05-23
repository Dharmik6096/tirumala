<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblBmcDispatchInspection;

/**
 * TblBmcDispatchInspectionSearch represents the model behind the search form about `app\modules\tankermovement\models\TblBmcDispatchInspection`.
 */
class TblBmcDispatchInspectionSearch extends TblBmcDispatchInspection {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'to_date', 'bmc_dispatch_inspection_code', 'inspection_date', 'vehicle_code', 'trip_code', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblBmcDispatchInspection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['vehicleCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bmc_dispatch_inspection', 'tbl_bmc_dispatch_inspection', 'tbl_bmc_dispatch_inspection');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'CAST(tbl_bmc_dispatch_inspection.inspection_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(tbl_bmc_dispatch_inspection.inspection_date as date)', $to_date]);
        }
        $query->andFilterWhere(['=', 'CAST(tbl_bmc_dispatch_inspection.inspection_date as date)', !empty($this->inspection_date) ? date('Y-m-d', strtotime($this->inspection_date)) : NULL]);



        $query->andFilterWhere(['like', 'tbl_vehicle_master.parsing_no', $this->vehicle_code])
                ->andFilterWhere(['like', 'trip_code', $this->trip_code])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

}
