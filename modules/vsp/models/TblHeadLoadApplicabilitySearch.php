<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblHeadLoadApplicability;

/**
 * TblHeadLoadApplicabilitySearch represents the model behind the search form about `app\modules\vsp\models\TblHeadLoadApplicability`.
 */
class TblHeadLoadApplicabilitySearch extends TblHeadLoadApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                //  [['code', 'created_at', 'created_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'head_load_code', 'sub_center_code'], 'safe'],
                // [['is_delete'], 'boolean'],
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
        $query = TblHeadLoadApplicability::find();
        $query->where(['head_load_code' => $this->head_load_code]);
        $query->orderBy(['wef_date' => SORT_DESC]);
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

        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'wef_date' => $this->wef_date,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'head_load_code', $this->head_load_code]);

        return $dataProvider;
    }

}
