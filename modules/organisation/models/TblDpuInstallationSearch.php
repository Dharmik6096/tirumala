<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDpuInstallation;

/**
 * TblDpuInstallationSearch represents the model behind the search form about `app\modules\organisation\models\TblDpuInstallation`.
 */
class TblDpuInstallationSearch extends TblDpuInstallation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inst_code', 'is_active'], 'integer'],
            [['inst_date', 'inst_by', 'remarks', 'attachment', 'dcs_code', 'union_code', 'soc_secretary', 'secretary_mobile', 'simcard_company', 'dpu_sim_mobile', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblDpuInstallation::find();

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
            'inst_code' => $this->inst_code,
            'inst_date' => $this->inst_date,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'inst_by', $this->inst_by])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'attachment', $this->attachment])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'soc_secretary', $this->soc_secretary])
            ->andFilterWhere(['like', 'secretary_mobile', $this->secretary_mobile])
            ->andFilterWhere(['like', 'simcard_company', $this->simcard_company])
            ->andFilterWhere(['like', 'dpu_sim_mobile', $this->dpu_sim_mobile])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
