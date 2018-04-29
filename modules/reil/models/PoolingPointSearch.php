<?php

namespace app\modules\reil\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\reil\models\PoolingPoint;

/**
 * PoolingPointSearch represents the model behind the search form about `app\modules\reil\models\PoolingPoint`.
 */
class PoolingPointSearch extends PoolingPoint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PlantCode', 'PlantName', 'BMCCode', 'BMCName', 'PPCode', 'PPName', 'VillageCode', 'IMEINo', 'DPU_INIT_DTTM', 'DPURate_update_DTTM_B', 'DPURate_update_DTTM_C', 'PPACTIVE', 'DPU_MEM_UPDATION_REQUIRED', 'MID', 'DPU_MID', 'DPUMem_Update_DTTM', 'UPDATEDBY', 'UPDATEDON'], 'safe'],
            [['RATEID_B', 'RATEID_C', 'DPU_RATE_ID_B', 'DPU_RATE_ID_C'], 'integer'],
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
        $query = PoolingPoint::find();

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
            'DPU_INIT_DTTM' => $this->DPU_INIT_DTTM,
            'RATEID_B' => $this->RATEID_B,
            'RATEID_C' => $this->RATEID_C,
            'DPU_RATE_ID_B' => $this->DPU_RATE_ID_B,
            'DPU_RATE_ID_C' => $this->DPU_RATE_ID_C,
            'DPURate_update_DTTM_B' => $this->DPURate_update_DTTM_B,
            'DPURate_update_DTTM_C' => $this->DPURate_update_DTTM_C,
            'DPUMem_Update_DTTM' => $this->DPUMem_Update_DTTM,
            'UPDATEDON' => $this->UPDATEDON,
        ]);

        $query->andFilterWhere(['like', 'PlantCode', $this->PlantCode])
            ->andFilterWhere(['like', 'PlantName', $this->PlantName])
            ->andFilterWhere(['like', 'BMCCode', $this->BMCCode])
            ->andFilterWhere(['like', 'BMCName', $this->BMCName])
            ->andFilterWhere(['like', 'PPCode', $this->PPCode])
            ->andFilterWhere(['like', 'PPName', $this->PPName])
            ->andFilterWhere(['like', 'VillageCode', $this->VillageCode])
            ->andFilterWhere(['like', 'IMEINo', $this->IMEINo])
            ->andFilterWhere(['like', 'PPACTIVE', $this->PPACTIVE])
            ->andFilterWhere(['like', 'DPU_MEM_UPDATION_REQUIRED', $this->DPU_MEM_UPDATION_REQUIRED])
            ->andFilterWhere(['like', 'MID', $this->MID])
            ->andFilterWhere(['like', 'DPU_MID', $this->DPU_MID])
            ->andFilterWhere(['like', 'UPDATEDBY', $this->UPDATEDBY]);

        return $dataProvider;
    }
}
