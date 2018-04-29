<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkCollection;

/**
 * TblMilkCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblMilkCollection`.
 */
class TblMilkCollectionSearch extends TblMilkCollection {

    /**
     * @inheritdoc
     */
    public $union_code;
    public $operator_fat, $operator_snf, $operator_qty, $operator_amount;

    public function rules() {
        return [
            [['milk_collection_code', 'sample_no', 'ack'], 'integer'],
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag', 'union_code', 'min_date', 'max_date'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'milk_type_code', 'operator_fat', 'operator_snf', 'operator_qty', 'operator_amount'], 'safe'],
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
        $query = TblMilkCollection::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode', 'memberCode', 'milkTypeCode']);


        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!empty($request['min_date']) && !empty($request['max_date'])) {
            $start_date = date('Y-m-d', strtotime($request['min_date']));
            $end_date = date('Y-m-d', strtotime($request['max_date']));
            if ($start_date != $end_date)
                $query->andFilterWhere(['between', 'CAST(date_time_of_collection AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', $start_date]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->shift != 3) {
            $query->andFilterWhere(['like', 'shift', $this->shift]);
        }

        //  Yii::$app->general->filterByDropdownRange($query, $this, ['fat', 'snf', 'qty', 'amount']);
        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'snf', $this->snf]);
        }
        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'qty', $this->qty]);
        }
        if (!empty($this->amount)) {
            $query->andFilterWhere([$this->operator_amount, 'amount', $this->amount]);
        }


        Yii::$app->general->filterByNumber($query, $this, ['rtpl']);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'milk_collection_code' => $this->milk_collection_code,
//            'milk_type_code' => $this->milk_type_code,
//            'fat' => $this->fat,
//            'snf' => $this->snf,
            'water' => $this->water,
//            'qty' => $this->qty,
//            'rtpl' => $this->rtpl,
//            'amount' => $this->amount,
//            'date_time_of_collection' => $this->date_time_of_collection,
            'date_time_of_recieve' => $this->date_time_of_recieve,
            'sample_no' => $this->sample_no,
            'ack' => $this->ack,
        ]);

        $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'auto_flag', $this->auto_flag])
                ->andFilterWhere(['like', 'village_code', $this->village_code])
                ->andFilterWhere(['like', 'type_of_data_receive', $this->type_of_data_receive])
                ->andFilterWhere(['like', 'rate_code', $this->rate_code])
                ->andFilterWhere(['like', 'error_log', $this->error_log])
                ->andFilterWhere(['like', 'milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['like', 'soc_bmc_flag', $this->soc_bmc_flag]);

//        echo $query->createCommand()->getRawSql();die;
        return $dataProvider;
    }

}
