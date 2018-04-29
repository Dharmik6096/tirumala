<?php

namespace app\modules\globalmaster\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblLandUnit;

/**
 * TblLandUnitSearch represents the model behind the search form about `app\models\TblLandUnit`.
 */
class TblLandUnitSearch extends TblLandUnit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            //[['conversion_factor'], 'number'],
            [['created_at', 'conversion_factor', 'land_unit', 'land_unit_code','land_unit_name', 'updated_at', 'created_by', 'updated_by'], 'safe'],
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
        $query = TblLandUnit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['land_unit_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['land_unit_name'=>SORT_ASC]],
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_land_unit.conversion_factor' => $this->conversion_factor,
            'tbl_land_unit.is_active' => $this->is_active,
            'tbl_land_unit.land_unit' => $this->land_unit,
        ]);

        $query->andFilterWhere(['like', 'land_unit_name', $this->land_unit_name])
                ->andFilterWhere(['like', 'tbl_land_unit.land_unit_code', $this->land_unit_code]);

        return $dataProvider;
    }
}
