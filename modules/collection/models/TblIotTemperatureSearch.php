<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblIotTemperature;

/**
 * TblIotTemperatureSearch represents the model behind the search form about `app\modules\collection\models\TblIotTemperature`.
 */
class TblIotTemperatureSearch extends TblIotTemperature {

    /**
     * @inheritdoc
     */
    public $operator_temperature;

    public function rules() {
        return [
            [['id', 'union_code', 'plant_code', 'mcc_code', 'bmc_code', 'dcs_code', 'date_time', 'device_id', 'temperature', 'originating_org_code', 'originating_org_type', 'originating_type', 'updated_at', 'updated_by', 'created_at', 'created_by', 'operator_temperature'], 'safe'],
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
        $this->load($params);
        $query = TblIotTemperature::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode']);


        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->temperature)) {
            $query->andFilterWhere([$this->operator_temperature, 'tbl_iot_temperature.temperature', $this->temperature]);
        }

        if (!empty($this->date_time))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time, 126)', date('Y-m-d', strtotime($this->date_time))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_iot_temperature.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name]);
        return $dataProvider;
    }

}
