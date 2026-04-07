<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\usermanagement\models\TblEiplAppMenuActions;

/**
 * TblEiplAppMenuActionsSearch represents the model behind the search form about `app\modules\usermanagement\models\TblEiplAppMenuActions`.
 */
class TblEiplAppMenuActionsSearch extends TblEiplAppMenuActions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_code', 'is_active', 'sequence_no', 'parent_code'], 'integer'],
            [['action_name', 'service_url', 'description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'app_type'], 'safe'],
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
        $query = TblEiplAppMenuActions::find();

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
            'action_code' => $this->action_code,
            'is_active' => $this->is_active,
            'sequence_no' => $this->sequence_no,
            'parent_code' => $this->parent_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'action_name', $this->action_name])
            ->andFilterWhere(['like', 'service_url', $this->service_url])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
