<?php

namespace app\modules\tms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblTask;

/**
 * TblTaskSearch represents the model behind the search form about `app\modules\tms\models\TblTask`.
 */
class TblTaskSearch extends TblTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_code', 'task_type_code', 'form_type_code', 'is_cancel', 'is_notified', 'originating_type'], 'integer'],
            [['task_performed_for', 'title', 'description', 'task_datetime', 'user_code', 'route_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'notified_datetime', 'pick_datetime', 'response_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblTask::find();

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
            'task_code' => $this->task_code,
            'task_type_code' => $this->task_type_code,
            'form_type_code' => $this->form_type_code,
            'task_datetime' => $this->task_datetime,
            'is_cancel' => $this->is_cancel,
            'is_notified' => $this->is_notified,
            'notified_datetime' => $this->notified_datetime,
            'pick_datetime' => $this->pick_datetime,
            'response_datetime' => $this->response_datetime,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'task_performed_for', $this->task_performed_for])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'user_code', $this->user_code])
            ->andFilterWhere(['like', 'route_code', $this->route_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
