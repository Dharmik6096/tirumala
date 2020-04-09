<?php

namespace app\modules\vendorapi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vendorapi\models\TblPreventCollectionData;

/**
 * TblPreventCollectionDataSearch represents the model behind the search form about `app\modules\vendorapi\models\TblPreventCollectionData`.
 */
class TblPreventCollectionDataSearch extends TblPreventCollectionData {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['prevent_collection_data_id', 'from_shift', 'to_shift'], 'integer'],
                [['from_date', 'to_date', 'union_code', 'plant_code', 'mcc_plant_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblPreventCollectionData::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_prevent_collection_data', 'tbl_prevent_collection_data');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->from_date))
            $query->andFilterWhere(['cast(tbl_prevent_collection_data.from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        if (!empty($this->to_date))
            $query->andFilterWhere(['cast(tbl_prevent_collection_data.to_date as date)' => date('Y-m-d', strtotime($this->to_date))]);
//        $query->andFilterWhere(['like', 'union_code', $this->union_code])
//                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
//                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code]);

        return $dataProvider;
    }

}
