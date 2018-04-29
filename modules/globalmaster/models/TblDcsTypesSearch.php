<?php

namespace app\modules\globalmaster\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblDcsTypes;

/**
 * TblDcsTypesSearch represents the model behind the search form about `app\models\TblDcsTypes`.
 */
class TblDcsTypesSearch extends TblDcsTypes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['dcs_type_code', 'created_at', 'dcs_type_name', 'updated_at', 'created_by', 'updated_by'], 'safe'],
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
        $query = TblDcsTypes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['dcs_type_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['dcs_type_name'=>SORT_ASC]],
        ]);
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_dcs_types.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'dcs_type_name', $this->dcs_type_name])
                ->andFilterWhere(['like', 'tbl_dcs_types.dcs_type_code', $this->dcs_type_code]);

        return $dataProvider;
    }
}
