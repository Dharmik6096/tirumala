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

    public $operator_fat, $operator_snf, $from_date, $to_date, $from_shift, $to_shift, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid', 'date_time_of_collection', 'shift_code', 'quality_datetime', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'device_id', 'version_no', 'originating_org_code', 'originating_org_type', 'milk_analyser_type_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'own_mcc_plant_code', 'own_bmc_code'], 'safe'],
            [['sample_no', 'retest_count', 'doc_no', 'auto_flag', 'originating_type', 'qlty_auto'], 'integer'],
            [['fat', 'snf', 'clr', 'water'], 'number'],
            [['operator_snf', 'operator_fat', 'from_date', 'to_date', 'from_shift', 'to_shift', 'bmc_ref_code'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'from_shift'], 'required', 'on' => ['delete']]
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
        Yii::$app->general->filterByOrg($query, $this, 'tbl_quality_collection', 'tbl_quality_collection', 'tbl_quality_collection');

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
        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_quality_collection.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        $query->andFilterWhere([
            'tbl_quality_collection.doc_no' => $this->doc_no,
            'tbl_quality_collection.sample_no' => $this->sample_no,
            'tbl_quality_collection.qlty_auto' => $this->qlty_auto,
        ]);
        $query->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->bmc_ref_code]);
        //$query->orderBy(['tbl_quality_collection.date_time_of_collection' => SORT_DESC, 'tbl_bmc.bmc_name' => SORT_ASC, 'tbl_quality_collection.doc_no' => SORT_ASC, 'tbl_quality_collection.sample_no' => SORT_ASC]);

        return $dataProvider;
    }

    public function deletesearch($params) {
        $query = TblQualityCollection::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->join('LEFT JOIN', 'tbl_bmc_collection', 'CAST(tbl_bmc_collection.date_time_of_collection as date) = CAST(tbl_quality_collection.date_time_of_collection as date) and tbl_quality_collection.shift_code = tbl_bmc_collection.shift_code and tbl_quality_collection.mcc_plant_code = tbl_bmc_collection.mcc_plant_code and tbl_quality_collection.bmc_code = tbl_bmc_collection.bmc_code and tbl_quality_collection.sample_no = tbl_bmc_collection.sample_no and tbl_quality_collection.doc_no = tbl_bmc_collection.doc_no');
        $this->load($params);

        if (!empty($this->bmc_code)) {
            $query->andWhere(['tbl_quality_collection.bmc_code' => $this->bmc_code]);
        } else {
            $query->andWhere('0=1');
        }

        Yii::$app->general->filterByOrg($query, $this, 'tbl_quality_collection', 'tbl_quality_collection', 'tbl_quality_collection');

        if (!empty($this->from_date) && !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['tbl_quality_collection.date_time_of_collection' => $from_date]);
        }
        $query->andWhere(['or', ['is', 'tbl_bmc_collection.data_post_status', NULL], ['=', 'tbl_bmc_collection.data_post_status', '0']]);

        return $dataProvider;
    }

}
