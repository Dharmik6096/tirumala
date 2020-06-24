<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblRouteMappingSources;

/**
 * TblRouteMappingSourcesSearch represents the model behind the search form about `app\modules\organisation\models\TblRouteMappingSources`.
 */
class TblRouteMappingSourcesSearch extends TblRouteMappingSources {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['route_code', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblRouteMappingSources::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'capacity' => $this->capacity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_active' => $this->is_active,
            'route_code' => $this->route_code,
            'to_type' => $this->to_type,
            'from_type' => $this->from_type,
            'to_dest' => $this->to_dest,
            'from_dest' => $this->from_dest,
        ]);


//        $query->andFilterWhere(['like', 'from_dest', $this->from_dest]);
//               ->andFilterWhere(['like', 'route_code', $this->route_code])
//                ->andFilterWhere(['like', 'from_type', $this->from_type])
//                ->andFilterWhere(['like', 'to_type', $this->to_type])
//                ->andFilterWhere(['like', 'to_dest', $this->to_dest])
//                ->andFilterWhere(['like', 'created_by', $this->created_by])
//                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

    public function deletesearch($params) {
        $this->load($params);
        $query = TblRouteMappingSources::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        $query->joinWith(['dcsCode']);
        $query->andWhere(['tbl_dcs.bmc_code' => $this->bmc_code]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'tbl_dcs.route_code', $this->route_code]);

        return $dataProvider;
    }

}
