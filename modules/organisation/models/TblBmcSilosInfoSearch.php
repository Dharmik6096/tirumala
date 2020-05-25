<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblBmcSilosInfo;

/**
 * TblBmcSilosInfoSearch represents the model behind the search form about `app\modules\organisation\models\TblBmcSilosInfo`.
 */
class TblBmcSilosInfoSearch extends TblBmcSilosInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bmc_silos_info_code', 'manufacturer_code', 'storage_capacity', 'chilling_capacity', 'milk_type_code', 'is_active', 'originating_type'], 'integer'],
            [['silo_no', 'description', 'model', 'wef_date', 'owning_type', 'module_name', 'module_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblBmcSilosInfo::find();

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
            'bmc_silos_info_code' => $this->bmc_silos_info_code,
            'manufacturer_code' => $this->manufacturer_code,
            'wef_date' => $this->wef_date,
            'storage_capacity' => $this->storage_capacity,
            'chilling_capacity' => $this->chilling_capacity,
            'milk_type_code' => $this->milk_type_code,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'silo_no', $this->silo_no])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'owning_type', $this->owning_type])
            ->andFilterWhere(['like', 'module_name', $this->module_name])
            ->andFilterWhere(['like', 'module_code', $this->module_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'x_col1', $this->x_col1])
            ->andFilterWhere(['like', 'x_col2', $this->x_col2])
            ->andFilterWhere(['like', 'x_col3', $this->x_col3])
            ->andFilterWhere(['like', 'x_col4', $this->x_col4])
            ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }
}
