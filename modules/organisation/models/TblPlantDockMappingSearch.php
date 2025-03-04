<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblPlantDockMapping;

/**
 * TblPlantDockMappingSearch represents the model behind the search form about `app\modules\organisation\models\TblPlantDockMapping`.
 */
class TblPlantDockMappingSearch extends TblPlantDockMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['plant_dock_mapping_code', 'dock_no', 'originating_type', 'union_code', 'plant_code', 'dock_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblPlantDockMapping::find();

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
            'plant_dock_mapping_code' => $this->plant_dock_mapping_code,
            'dock_no' => $this->dock_no,
        ]);

        $query->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'dock_name', $this->dock_name]);

        return $dataProvider;
    }

}
