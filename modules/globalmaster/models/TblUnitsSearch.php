<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblUnits;

/**
 * TblUnitsTypeSearch represents the model behind the search form about `app\models\TblUnits`.
 */
class TblUnitsSearch extends TblUnits {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active'], 'integer'],
                [['unit_code', 'created_at', 'short_name', 'unit_name', 'updated_at', 'created_by', 'updated_by', 'union_code'], 'safe'],
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
        $query = TblUnits::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['unit_name' => SORT_ASC]],
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['unit_name' => SORT_ASC]],
        ]);
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_units.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_units.unit_name', $this->unit_name])
                ->andFilterWhere(['like', 'tbl_units.unit_code', $this->unit_code])
                ->andFilterWhere(['like', 'tbl_units.short_name', $this->short_name])
                ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code]);

        return $dataProvider;
    }

}
