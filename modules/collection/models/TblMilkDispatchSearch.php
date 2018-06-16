<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkDispatch;

/**
 * TblMilkDispatchSearch represents the model behind the search form about `app\modules\collection\models\TblMilkDispatch`.
 */
class TblMilkDispatchSearch extends TblMilkDispatch {

    public $from_date, $to_date, $from_shift, $to_shift;
    /**
     * @inheritdoc
     */
    public $union_code;
    public $operator_fat, $operator_snf, $operator_qty;

    public function rules() {
        return [
            [['milk_dispatch_code', 'milk_type_code', 'sample_no'], 'integer'],
            [['dcs_code', 'bmc_code', 'shift', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'union_code'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'operator_fat', 'operator_snf', 'operator_qty', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
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
        $query = TblMilkDispatch::find();
        $request = Yii::$app->request->queryParams;
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
        $query->joinWith(['dcsCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .=' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .=' ' . $to_shift;            
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);            
        }
        // Yii::$app->general->filterByDropdownRange($query, $this, ['fat', 'snf', 'qty']);
        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'tbl_milk_dispatch.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_milk_dispatch.snf', $this->snf]);
        }
        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_milk_dispatch.qty', $this->qty]);
        }
        Yii::$app->general->filterByNumber($query, $this, ['water']);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_milk_dispatch.milk_dispatch_code' => $this->milk_dispatch_code,
//            'milk_type_code' => $this->milk_type_code,
//            'fat' => $this->fat,
//            'snf' => $this->snf,
//            'water' => $this->water,
//            'qty' => $this->qty,
            // 'date_time_of_collection' => $this->date_time_of_collection,
            'tbl_milk_dispatch.date_time_of_recieve' => $this->date_time_of_recieve,
            'tbl_milk_dispatch.sample_no' => $this->sample_no,
        ]);

        $query->andFilterWhere(['like', 'tbl_milk_dispatch.bmc_code', $this->bmc_code])
//            ->andFilterWhere(['like', 'shift', $this->shift])
                ->andFilterWhere(['like', 'tbl_milk_dispatch.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_milk_dispatch.milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_dispatch.type_of_data_receive', $this->type_of_data_receive]);

        return $dataProvider;
    }

}
