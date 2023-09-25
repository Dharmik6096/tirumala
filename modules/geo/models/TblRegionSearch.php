<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblRegion;

/**
 * TblRegionSearch represents the model behind the search form about `app\modules\geo\models\TblRegion`.
 */
class TblRegionSearch extends TblRegion {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['region_code', 'state_code', 'region_name', 'local_name', 'address', 'local_address', 'description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code'], 'safe'],
            [['is_active'], 'integer'],
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
        $query = TblRegion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['stateCode']);

        if (Yii::$app->session->get('Unions') !== '')
            $query->andFilterWhere([ 'tbl_region.union_code' => explode(',', Yii::$app->session->get('Unions'))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_region.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'region_code', $this->region_code])
                ->andFilterWhere(['like', 'tbl_region.region_name', $this->region_name])
                ->andFilterWhere(['like', 'tbl_states.state_name', $this->state_code])
                ->andFilterWhere(['like', 'local_name', $this->local_name])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'local_address', $this->local_address])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);
        return $dataProvider;
    }
}
