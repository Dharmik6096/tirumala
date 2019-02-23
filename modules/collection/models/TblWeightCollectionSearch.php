<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblWeightCollection;

/**
 * TblWeightCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblWeightCollection`.
 */
class TblWeightCollectionSearch extends TblWeightCollection {

    public $operator_qty;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid', 'producer_flag', 'collection_date', 'shift_code', 'fault_flag', 'union_code', 'plant_code', 'mcc_code', 'bmc_code', 'route_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'device_id'], 'safe'],
            [['sample_no', 'milk_type', 'milk_quality_type', 'quantity_mode', 'rejected_can', 'converted_quantity_mode', 'doc_no', 'auto_flag'], 'safe'],
            [['quantity', 'cans', 'rejected_quantity', 'converted_quantity', 'operator_qty', 'min_date', 'max_date'], 'safe'],
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
        $query = TblWeightCollection::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'shiftCode', 'milkTypeCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');



        if (!empty($this->quantity)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_weight_collection.quantity', $this->quantity]);
        }
        if (!empty($request['min_date']) && !empty($request['max_date'])) {
            $start_date = date('Y-m-d', strtotime($request['min_date']));
            $end_date = date('Y-m-d', strtotime($request['max_date']));
            if ($start_date != $end_date)
                $query->andFilterWhere(['between', 'CAST(tbl_weight_collection.collection_date AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_collection.collection_date, 126)', $start_date]);
        }
        if (!empty($this->collection_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_collection.collection_date, 126)', date('Y-m-d', strtotime($this->collection_date))]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tbl_weight_collection.sample_no' => $this->sample_no,
            'tbl_weight_collection.doc_no' => $this->doc_no,
        ]);
        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_code])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type])
                ->andFilterWhere(['like', 'tbl_weight_collection.sample_no', $this->sample_no]);

        return $dataProvider;
    }

}
