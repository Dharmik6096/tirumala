<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblAllowDcsManualCollectionRange;

/**
 * TblAllowDcsManualCollectionRangeSearch represents the model behind the search form about `app\modules\organisation\models\TblAllowDcsManualCollectionRange`.
 */
class TblAllowDcsManualCollectionRangeSearch extends TblAllowDcsManualCollectionRange {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['manual_collection_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'status', 'remark', 'request_type'], 'safe'],
                [['is_weight_manual', 'is_quality_manual', 'originating_type'], 'integer'],
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
        $query = TblAllowDcsManualCollectionRange::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_allow_dcs_manual_collection_range', 'tbl_allow_dcs_manual_collection_range', 'tbl_allow_dcs_manual_collection_range');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'is_weight_manual' => $this->is_weight_manual,
            'is_quality_manual' => $this->is_quality_manual,
            'status' => $this->status,
            'request_type' => $this->request_type,
        ]);
        return $dataProvider;
    }

}
