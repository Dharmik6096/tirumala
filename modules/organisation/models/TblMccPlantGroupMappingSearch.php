<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblMccPlantGroupMapping;

/**
 * TblMccPlantGroupMappingSearch represents the model behind the search form about `app\modules\organisation\models\TblMccPlantGroupMapping`.
 */
class TblMccPlantGroupMappingSearch extends TblMccPlantGroupMapping
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mcc_plant_mapping_code', 'is_active'], 'integer'],
            [['mcc_plant_code', 'p_mcc_plant_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblMccPlantGroupMapping::find();

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
            'mcc_plant_mapping_code' => $this->mcc_plant_mapping_code,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'p_mcc_plant_code', $this->p_mcc_plant_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
