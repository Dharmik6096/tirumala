<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblBmcDispatch;

/**
 * TblBmcDispatchSearch represents the model behind the search form about `app\modules\collection\models\TblBmcDispatch`.
 */
class TblBmcDispatchSearch extends TblBmcDispatch {

    public $operator_fat, $operator_snf, $operator_qty;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_dispatch_code', 'milk_test', 'alcohole_test', 'dispatch_shift', 'milk_type_code', 'milk_quality_type_code'], 'safe'],
            [['fat', 'snf', 'mbrt', 'temprature', 'actual_qty', 'dispatch_qty'], 'safe'],
            [['vehicle_code', 'vehicle_in_time', 'vehicle_out_time', 'destination_code', 'destination_type', 'dispatch_datetime', 'bmc_code', 'route_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'operator_fat', 'operator_snf', 'operator_qty'], 'safe'],
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
        $query = TblBmcDispatch::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['bmcCode.tblMccPlant', 'bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_plant', 'tbl_mcc_plant');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'snf', $this->snf]);
        }
        if (!empty($this->dispatch_qty)) {
            $query->andFilterWhere([$this->operator_qty, 'dispatch_qty', $this->dispatch_qty]);
        }
        if (!empty($this->dispatch_datetime)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), dispatch_datetime, 126)', date('Y-m-d', strtotime($this->dispatch_datetime))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'bmc_dispatch_code' => $this->bmc_dispatch_code,
            'milk_test' => $this->milk_test,
            'dispatch_shift' => $this->dispatch_shift,
            'milk_type_code' => $this->milk_type_code,
            'milk_quality_type_code' => $this->milk_quality_type_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_bmc_dispatch.destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch.destination_type', $this->destination_type])
                ->andFilterWhere(['like', 'tbl_dcs_subcenter_bmc_info.bmc_name', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch.route_code', $this->route_code]);

        return $dataProvider;
    }

}
