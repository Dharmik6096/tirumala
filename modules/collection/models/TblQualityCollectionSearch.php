<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblQualityCollection;

/**
 * TblQualityCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblQualityCollection`.
 */
class TblQualityCollectionSearch extends TblQualityCollection {

    public $operator_fat, $operator_snf;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid', 'collection_date', 'shift_code', 'quality_datetime', 'union_code', 'plant_code', 'mcc_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'device_id'], 'safe'],
            [['sample_no', 'retest_count', 'doc_no', 'auto_flag'], 'safe'],
            [['fat', 'snf', 'clr', 'water', 'operator_fat', 'operator_snf'], 'safe'],
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
        $query = TblQualityCollection::find();

        $request = Yii::$app->request->queryParams;
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
            $query->andFilterWhere([$this->operator_fat, 'tbl_quality_collection.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_quality_collection.snf', $this->snf]);
        }
        if (!empty($request['min_date']) && !empty($request['max_date'])) {
            $start_date = date('Y-m-d', strtotime($request['min_date']));
            $end_date = date('Y-m-d', strtotime($request['max_date']));
            if ($start_date != $end_date)
                $query->andFilterWhere(['between', 'CAST(tbl_quality_collection.collection_date AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_quality_collection.collection_date, 126)', $start_date]);
        }
        if (!empty($this->collection_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_quality_collection.collection_date, 126)', date('Y-m-d', strtotime($this->collection_date))]);

        $query->andFilterWhere([
            'tbl_quality_collection.doc_no' => $this->doc_no,
            'tbl_quality_collection.sample_no' => $this->sample_no,
        ]);
        $query->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code]);
        $query->orderBy('tbl_quality_collection.collection_date desc');
        return $dataProvider;
    }

}
