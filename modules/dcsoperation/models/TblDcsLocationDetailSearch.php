<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblDcsLocationDetail;

/**
 * TblDcsLocationDetailSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsLocationDetail`.
 */
class TblDcsLocationDetailSearch extends TblDcsLocationDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dcs_location_detail_code', 'date_time_of_location', 'latitude', 'longitude', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'received_timestamp', 'originating_type'], 'safe'],
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
        $query = TblDcsLocationDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs_location_detail', 'tbl_dcs_location_detail', 'tbl_dcs_location_detail', 'tbl_dcs_location_detail');

        if (!empty($this->date_time_of_location)) {
            $query->andFilterWhere(['CAST(date_time_of_location as date)' => date('Y-m-d', strtotime($this->date_time_of_location))]);
        } else {
            $query->andWhere(['between', 'CAST(date_time_of_location as date)', date('Y-m-d', strtotime('-10 days')), date('Y-m-d')]);
        }

        if (!empty($this->received_timestamp)) {
            $query->andFilterWhere(['CAST(received_timestamp as date)' => date('Y-m-d', strtotime($this->received_timestamp))]);
        }
        $query->andFilterWhere(['like', 'latitude', $this->latitude])
                ->andFilterWhere(['like', 'longitude', $this->longitude])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3]);

        return $dataProvider;
    }

}
